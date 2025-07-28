<x-app-layout>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<div class="flex-1 p-6">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            text-align: center;
        }
        .header {
            background-color: #1f3a93;
            color: white;
            padding: 10px;
            font-size: 18px;
            margin-bottom: 40px;
            width: 50%;
            margin-left: auto;
            margin-right: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .container {
            width: 50%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .form-section {
            background-color: #f4b042;
            padding: 20px;
            border-radius: 8px;
            position: relative;
        }
        .form-header {
            background-color: #f4c067;
            padding: 10px;
            font-weight: bold;
            border-radius: 8px;
            width: 100%;
            display: inline-block;
        }
        .form-group {
            margin: 10px 0;
            text-align: left;
        }
        .form-group label {
            display: block;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            max-height: 50px;
            overflow-y: auto;
        }

        .button-container {
            margin-top: 15px;
        }
        .button {
            background-color: #1f3a93;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .cancel {
            background-color: #666;
        }
    </style>
    <div class="w-[50%] mx-auto">
        <x-prims-sub-header>Add Medicine</x-prims-sub-header>
    </div>
    <div class="container">
        <form action="{{ route('inventory.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="brand">Brand:</label>
                    <input type="text" id="brand" name="brand" placeholder="e.g., Biogesic">
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" placeholder="e.g., Paracetamol" required>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="form-group">
                    <label for="category">Category:</label>
                    <div class="flex flex-col gap-2">
                        <select id="category" name="category">
                            <option value="Analgesic">Analgesic</option>
                            <option value="Antiemetic">Antiemetic</option>
                            <option value="Antihistamine">Antihistamine</option>
                            <option value="Antihypertensive">Antihypertensive</option>
                            <option value="Antipyretic">Antipyretic</option>
                            <option value="Antitussive">Antitussive</option>
                            <option value="Bronchodilator">Bronchodilator</option>
                            <option value="Decongestant">Decongestant</option>
                            <option value="Electrolyte Solution">Electrolyte Solution</option>
                            <option value="Expectorant">Expectorant</option>
                            <option value="Gastrointestinal">Gastrointestinal</option>
                            <option value="Muscle Relaxant">Muscle Relaxant</option>
                            <option value="NSAID">NSAID</option>
                            <option value="Other">Others (Specify below)</option>
                        </select>
                        <input type="text" id="category_other" name="category_other" placeholder="Enter category" style="display: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="dosage_form">Dosage Form:</label>
                    <div class="flex flex-col gap-2">
                        <select id="dosage_form" name="dosage_form" placeholder="e.g., Tablet" onchange="updateUnitOptions()">
                            <option value="Tablet">Tablet</option>
                            <option value="Capsule">Capsule</option>
                            <option value="Syrup">Syrup</option>
                            <option value="Suspension">Nebulizer Solution</option>
                            <option value="Injection">Lozenge</option>
                            <option value="Oral Solution">Oral Solution</option>
                            <option value="Other">Others (Specify below)</option>
                        </select>
                        <input type="text" id="dosage_form_other" name="dosage_form_other" placeholder="Enter dosage form" style="display: none;">
                    </div>
                </div>
                <div class="form-group">
                    <label for="dosage_strength">Strength:</label>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" id="strength_number" name="strength_number" placeholder="e.g., 500" required>
                        <select id="strength_unit" name="strength_unit">
                            <option value="mg">mg</option>
                            <option value="mcg">mcg</option>
                            <option value="mL">mL</option>
                            <option value="mcg/mL">mcg/mL</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="form-group">
                    <label for="date_supplied">Date Supplied:</label>
                    <input type="text" id="date_supplied" name="date_supplied" placeholder="MM/DD/YYYY" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity Received (each):</label>
                    <input type="number" id="quantity" name="quantity_received" placeholder="e.g., 100" required>
                </div>
            </div>
            <div class="mb-4">
                <div class="form-group">
                    <label for="expiration">Expiration Date:</label>
                    <input type="text" id="expiration" name="expiration_date" placeholder="MM/DD/YYYY" required>
                </div>
            </div>
            <div class="button-container pt-4">
                <x-button onclick="window.location.href='{{ route('medical-inventory') }}';">
                    Cancel
                </x-button>
                <x-button type="submit">Submit</x-button>
            </div>
        </form>

    </div>
    <script>
        document.getElementById('category').addEventListener('change', function() {
            var otherInput = document.getElementById('category_other');
            if (this.value === 'Other') {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
                otherInput.value = '';
            }
        })
        
        document.getElementById('dosage_form').addEventListener('change', function() {
            var otherInput = document.getElementById('dosage_form_other');
            if (this.value === 'Other') {
                otherInput.style.display = 'block';
            } else {
                otherInput.style.display = 'none';
                otherInput.value = '';
            }
        })
        
        flatpickr("#date_supplied", {
            dateFormat: "m/d/Y", 
            allowInput: true,
        });

        flatpickr("#expiration", {
            dateFormat: "m/d/Y", 
            allowInput: true,
        });
    </script>
</x-app-layout>