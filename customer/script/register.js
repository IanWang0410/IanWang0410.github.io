function fetchCountries() {
    fetch('http://54.199.33.241/gas_html/country.php')
    .then(response => response.json())
    .then(data => {
        const countries = data.country;
        const countrySet = new Set(); // Use a Set to track already added country_id
        const selectElement = document.getElementById('citySpinner');

        // Clear existing options first
        selectElement.innerHTML = '<option selected>選擇縣市</option>';

        // Populate dropdown, ensuring no duplicates
        countries.forEach(country => {
            if (!countrySet.has(country.country_id)) {
                const option = document.createElement('option');
                option.value = country.country_id;
                option.textContent = country.country_name;
                selectElement.appendChild(option);
                countrySet.add(country.country_id);
            }
        });
    })
    .catch(error => console.error('Error:', error));
}

function fetchCitiesByCountry(countryName) {
    // Encode the country name to ensure the URL is properly formatted
    // const encodedCountryName = encodeURIComponent(countryName);
    const url = `http://54.199.33.241/gas_html/city.php?country_name=${countryName}`;
    // console.log(url);

    fetch(url)
    .then(response => { // first `.then` should handle the response and convert it to JSON
        // console.log(response);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json(); // Convert the response to JSON
    })
    .then(data => { // now `data` is the JSON object you expect
        // console.log(data);
        const selectElement = document.getElementById('districtSpinner'); // Correctly defining selectElement

        // Ensure selectElement is not null before attempting to use it
        if(selectElement) {
            selectElement.innerHTML = '<option selected>選擇區域</option>';  // Clearing the select element before adding new options
            // Make sure there is a 'city' key in the data and it is an array
            if (data && Array.isArray(data.city)) {
                data.city.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.city_id;
                    option.textContent = city.city_name;
                    selectElement.appendChild(option);
                });
            } else {
                // Log an error or show user-friendly message here
                console.error('The "city" property is not found or not an array in the fetched data');
            }
        } else {
            console.error('City select element not found.');
        }
    })
    .catch(error => console.error('Error fetching cities:', error));
}

function fetchCompanies() {
    fetch('http://54.199.33.241/gas_html/company.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Log the full data for debugging
        if (data && Array.isArray(data.company)) {
            const companiesSelect = document.getElementById('companies');
            if (companiesSelect) {
                // Clear the select before adding new options
                companiesSelect.innerHTML = '<option selected>請選擇公司</option>';
                // Iterate over each company and create a new <option> element
                data.company.forEach(company => {
                    let option = document.createElement('option');
                    option.value = company.COMPANY_Id;
                    option.textContent = company.COMPANY_Name;
                    companiesSelect.appendChild(option);
                });
            }
        } else {
            // You may want to handle the case where there is no company data, or it's not an array
            console.error('Company data is unavailable or not in the expected format.');
        }
    })
    .catch(error => {
        console.error('There was an error fetching the company list:', error);
    });
}

function submitRegistrationForm() {
    const form = document.getElementById('registrationForm');
    
    // Get the full address
    const fullAddress = getFullAddress();
    
    // If you want to include the full address in the form data being submitted:
    const formData = new FormData(form);
    formData.append('fullAddress', fullAddress); // Add the full address to your FormData
    
    fetch('http://54.199.33.241/gas_html/customer_register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(text => {
        console.log(text); // Log the server's response
        if (text === 'Registration failed') {
            alert('Registration failed. Please try again.');
            window.location.href = 'login.html'; // Redirect upon success
        } else {
            alert('Registration successful.');
            window.location.href = "login.html"
        } 
    })
    .catch(error => {
        console.error('Error during registration:', error);
        alert('An error occurred during registration.');
    });
}

function checkPasswordMatch() {
    var password = document.getElementById('register_pass_input').value; // Assuming your password input has the id="password"
    var confirmPassword = document.getElementById('register_pass_con_input').value; // Assuming your confirm password input has the id="confirmPassword"
    
    if (password && confirmPassword) {
        if (password === confirmPassword) {
            // Passwords match.
            console.log("Passwords match."); // For demonstration; use something more user-friendly in production, like a success message or visual indicator.
            // Optionally, remove error visuals if present.
        } else {
            // Passwords do not match.
            console.error("Passwords do not match."); // For demonstration; use something more user-friendly in production, like an error message or visual indicator.
            // Optionally, display error visuals.
        }
    } else {
        console.warn("One of the passwords is missing."); // For demonstration; handle this case as needed.
        // Optionally, handle both fields being empty as a special case.
    }
}

function getFullAddress() {
    // Assuming the select element has complete option elements like: <option value="valueProperty">Text Content</option>
    const citySelect = document.getElementById('citySpinner');
    const districtSelect = document.getElementById('districtSpinner');
    
    // Get the selected text content from the city and district dropdowns
    const city = citySelect.options[citySelect.selectedIndex].text;
    const district = districtSelect.options[districtSelect.selectedIndex].text;
    
    const address = document.getElementById('register_addr_input').value;
    const floorNumber = document.getElementById('floorText').value;
    
    const fullAddress = `${city}${district}${address}${floorNumber ? `（${floorNumber}樓）` : ""}`;
    // Log the full address to the console
    console.log(fullAddress);
    return(fullAddress);
}