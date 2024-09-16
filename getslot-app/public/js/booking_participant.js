// booking_participant.js
document.addEventListener('DOMContentLoaded', function() {
    const totalParticipantInput = document.getElementById('total_participant');
    const participantFormsContainer = document.getElementById('participant-forms');
    const plusButton = document.getElementById('plus');
    const minusButton = document.getElementById('minus');
    const countText = document.getElementById('count-text');
    let currentValue = parseInt(totalParticipantInput.value);

    function createParticipantForms(count) {
        console.log('create')
        participantFormsContainer.innerHTML = ''; // Clear previous forms
        for (let i = 0; i < count; i++) {
            const participantForm = `
            <div class="flex flex-col gap-2 p-4 rounded-lg border border-gray-300" style="border-radius: 15px">
                <div class="flex justify-between items-center">
                    <h3 class="font-bold text-lg">Participant ${i + 1}</h3>
                    <button type="button" class="toggle-button" data-target="participant-fields-${i}">
                         <img src="${assetPath}/assets/images/icons/square-top-down-svgrepo.svg" class="w-6 h-6" alt="icon">
                    </button>
                </div>
                <div id="participant-fields-${i}" class="participant-fields">
                    <div class="flex flex-col gap-[6px]">
                        <label for="participant_name_${i}" class="font-semibold text-sm leading-[21px]">Full Name</label>
                        <div class="flex items-center rounded-full px-5 gap-[10px] bg-[#F8F8F9] transition-all duration-300 focus-within:ring-1 focus-within:ring-[#F97316]">
                            <img src="${assetPath}/assets/images/icons/ghost-smile.svg" class="w-6 h-6" alt="icon">
                            <input type="text" name="participants[${i}][participant_name]" id="participant_name_${i}"
                                class="appearance-none outline-none py-[14px] !bg-transparent w-full font-semibold text-sm leading-[21px] placeholder:font-normal placeholder:text-[#13181D]"
                                placeholder="full name">
                        </div>
                    </div>
                    <div class="flex flex-col gap-[6px]">
                        <label for="identity_user_${i}" class="font-semibold text-sm leading-[21px]">Identity User</label>
                        <div class="flex items-center rounded-full px-5 gap-[10px] bg-[#F8F8F9] transition-all duration-300 focus-within:ring-1 focus-within:ring-[#F97316]">
                            <img src="${assetPath}/assets/images/icons/user-id.svg" class="w-6 h-6" alt="icon">
                            <input type="text" name="participants[${i}][identity_user]" id="identity_user_${i}"
                                class="appearance-none outline-none py-[14px] !bg-transparent w-full font-semibold text-sm leading-[21px] placeholder:font-normal placeholder:text-[#13181D]"
                                placeholder="NIK / passport">
                        </div>
                    </div>
                    <div class="flex flex-col gap-[6px]">
                        <label for="contingen_${i}" class="font-semibold text-sm leading-[21px]">Contingen</label>
                        <div class="flex items-center rounded-full px-5 gap-[10px] bg-[#F8F8F9] transition-all duration-300 focus-within:ring-1 focus-within:ring-[#F97316]">
                            <img src="${assetPath}/assets/images/icons/share-circle.svg" class="w-6 h-6" alt="icon">
                            <input type="text" name="participants[${i}][contingen]" id="contingen_${i}"
                                class="appearance-none outline-none py-[14px] !bg-transparent w-full font-semibold text-sm leading-[21px] placeholder:font-normal placeholder:text-[#13181D]"
                                placeholder="club / contingen">
                        </div>
                    </div>
                    <div class="flex flex-col gap-[6px]">
                        <label for="type_id_${i}" class="font-semibold text-sm leading-[21px]">Type</label>
                        <div class="flex items-center rounded-full px-5 gap-[10px] bg-[#F8F8F9] transition-all duration-300 focus-within:ring-1 focus-within:ring-[#F97316]">
                            <img src="${assetPath}/assets/images/icons/box-minimalistic.svg" class="w-6 h-6" alt="icon">
                          <select name="participants[${i}][type_id]" id="type_id_${i}" data-index="${i}"
                            class="type-select appearance-none outline-none py-[14px] !bg-transparent w-full font-semibold text-sm leading-[21px] placeholder:font-normal placeholder:text-[#13181D]">
                             <option value="" disabled selected>Select Type</option>
                            ${typeOptionsHtml}
                        </select>
                        </div>
                    </div>
                    <div class="flex flex-col gap-[6px]">
                        <label for="initial_id_${i}" class="font-semibold text-sm leading-[21px]">Class</label>
                        <div class="flex items-center rounded-full px-5 gap-[10px] bg-[#F8F8F9] transition-all duration-300 focus-within:ring-1 focus-within:ring-[#F97316]">
                            <img src="${assetPath}/assets/images/icons/box-minimalistic.svg" class="w-6 h-6" alt="icon">
                            <select name="participants[${i}][initial_id]" id="initial_id_${i}"
                                class="initial-select appearance-none outline-none py-[14px] !bg-transparent w-full font-semibold text-sm leading-[21px] placeholder:font-normal placeholder:text-[#13181D]">
                               <option value="" disabled selected>Select Class</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            `;
            participantFormsContainer.insertAdjacentHTML('beforeend', participantForm);
        }

        // Re-attach event listeners after new forms are created
        attachEventListeners();
    }

    function attachEventListeners() {
        // Add event listeners to the Type select dropdowns
        document.querySelectorAll('.type-select').forEach(selectElement => {
            selectElement.addEventListener('change', function() {
                const typeId = this.value;
                const index = this.getAttribute('data-index');
                const initialSelect = document.getElementById(`initial_id_${index}`);

                if (typeId) {
                    // Make an AJAX request to get the initials based on the selected type
                    fetch(`/get-initials/${typeId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear previous options
                            initialSelect.innerHTML =
                                '<option value="" disabled selected>Select Class</option>';

                            // Populate the Class (Initial) dropdown with the data
                            data.forEach(initial => {
                                const option = document.createElement('option');
                                option.value = initial.id;
                                option.textContent = initial.name;
                                initialSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching initials:', error);
                        });
                }
            });
        });

        // Add event listeners to toggle buttons
        const toggleButtons = document.querySelectorAll('.toggle-button');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetElement = document.getElementById(targetId);
                if (targetElement.style.display === 'none') {
                    targetElement.style.display = 'block';
                    this.querySelector('img').src =
                        `${assetPath}/assets/images/icons/square-top-down-svgrepo.svg`; // Change icon to collapse
                } else {
                    targetElement.style.display = 'none';
                    this.querySelector('img').src =
                        `${assetPath}/assets/images/icons/square-bottom-down-svgrepo.svg`; // Change icon to expand
                }
            });
        });
    }

    plusButton.addEventListener("click", () => {
        currentValue++;
        totalParticipantInput.value = currentValue;
        countText.textContent = currentValue;
        createParticipantForms(currentValue);
    });

    minusButton.addEventListener("click", () => {
        if (currentValue > 1) { // Ensure the count doesn't go below 1
            currentValue--;
            totalParticipantInput.value = currentValue;
            countText.textContent = currentValue;
            createParticipantForms(currentValue);
        }
    });

    // Inisialisasi form partisipan dengan nilai awal
    createParticipantForms(currentValue);
});