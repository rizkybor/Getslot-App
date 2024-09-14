<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Jobs\SendBookingApprovedEmail;
use App\Models\BookingTransaction;
use App\Models\Initial;
use App\Models\Ticket;
use App\Models\Type;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\Log;


class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationBadge(): ?string
    {
        return BookingTransaction::where('is_paid', false)->count();
    }

    protected static ?string $navigationGroup = 'Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Wizard::make([
                    Step::make('Ticket Amount')->schema([
                        Select::make('ticket_id')
                            ->relationship('ticket', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive(),

                        TextInput::make('total_participant')
                            ->required()
                            ->numeric()
                            ->prefix('People')
                            ->reactive(),
                    ]),

                    Step::make('Booker Information')->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('phone_number')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('booking_trx_id')
                            ->required()
                            ->maxLength(255),

                    ]),

                    Step::make('Participant Registration')->schema([
                        Repeater::make('bookingParticipant')
                        ->relationship('bookingParticipant')
                            ->schema([
                                TextInput::make('participant_name')
                                    ->label('Participant Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('identity_user')
                                    ->label('Identity Number')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('contingen')
                                    ->label('Contingen')
                                    ->required()
                                    ->maxLength(255),


                                Select::make('type_id')
                                    ->label('Type')
                                    ->options(Type::query()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive(),

                                Select::make('initial_id')
                                    ->label('Class')
                                    ->options(Initial::query()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        // Get the current price of the selected Initial
                                        $initial = Initial::find($state);
                                        $price = $initial ? $initial->price : 0;
                    
                                        // Update the total amount by recalculating it based on the form state
                                        $participants = $get('participants');
                                        $totalAmount = collect($participants)->sum(function ($participant) {
                                            $initial = Initial::find($participant['initial_id']);
                                            return $initial ? $initial->price : 0;
                                        });
                    
                                        // Set the new total amount
                                        $set('total_amount', $totalAmount);
                                    }),
                            ])
                            ->createItemButtonLabel('Add Participant')
                            ->minItems(fn(callable $get) => is_numeric($get('total_participant')) ? (int) $get('total_participant') : 0)
                            ->maxItems(fn(callable $get) => is_numeric($get('total_participant')) ? (int) $get('total_participant') : null)
                            ->columns(1)
                            ->grid(2)
                            ->collapsed()
                            ->afterStateUpdated(function (array $state, callable $get, callable $set) {
                                // Recalculate total amount when any participant is added or removed
                                $participants = $get('participants');
                                $totalAmount = collect($participants)->sum(function ($participant) {
                                    $initial = Initial::find($participant['initial_id']);
                                    return $initial ? $initial->price : 0;
                                });
                    
                                // Set the new total amount
                                $set('total_amount', $totalAmount);
                            }),
                            TextInput::make('total_amount')
                            ->required()
                            ->disabled()
                            ->numeric()
                            ->prefix('IDR')
                            ->readOnly()
                            ->helperText('Harga sudah include PPN 11%'),
                    ]),
                    Step::make('Payment Information')->schema([
                        ToggleButtons::make('is_paid')
                            ->label('Apakah sudah membayar ?')
                            ->boolean()
                            ->grouped()
                            ->icons([
                                'True' => 'heroicon-o-check',
                                'False' => 'heroicon-o-x-mark',
                            ])
                            ->required(),
                        FileUpload::make('proof')
                            ->image()
                            ->required(),
                        DatePicker::make('started_at')
                            ->required(),
                    ])
                ])
                    ->columnSpan('full')
                    ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                ImageColumn::make('ticket.thumbnail'),

                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('booking_trx_id')
                    ->searchable(),

                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Terverifikasi'),
            ])
            ->filters([
                //
                SelectFilter::make('ticket_id')
                    ->label('Ticket')
                    ->relationship('ticket', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->action(function (BookingTransaction $record) {
                        $record->is_paid = true;
                        $record->save();

                        SendBookingApprovedEmail::dispatch($record);

                        // Custom Notification
                        Notification::make()
                            ->title('Ticket Approved')
                            ->success()
                            ->body('The ticket has been successfully approved')
                            ->send();
                    })
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn(BookingTransaction $record) => ! $record->is_paid),

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
