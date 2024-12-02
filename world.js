document.addEventListener("DOMContentLoaded", () => {
    const lookupButton = document.getElementById("lookup");
    const lookupCitiesButton = document.getElementById("lookup-cities"); 
    const countryInput = document.getElementById("country");
    const resultDiv = document.getElementById("result");

    // Deals with Lookup button click for country information
    lookupButton.addEventListener("click", () => {
        const country = countryInput.value.trim();
        if (!country) {
            resultDiv.innerHTML = "<p>Please enter a country name.</p>";
            return;
        }

        fetchData(country, "country");
    });

    // Deals with Lookup Cities button click for city information
    lookupCitiesButton.addEventListener("click", () => {
        const country = countryInput.value.trim();
        if (!country) {
            resultDiv.innerHTML = "<p>Please enter a country name.</p>";
            return;
        }

        fetchData(country, "cities");
    });

    // Get the data based on the 'lookup' parameter (country or cities)
    function fetchData(country, lookupType) {
        const url = `world.php?country=${encodeURIComponent(country)}&lookup=${lookupType}`;
        fetch(url)
            .then(response => response.text())
            .then(data => {
                resultDiv.innerHTML = data ? data : "<p>No results found.</p>";
            })
            .catch(error => {
                resultDiv.innerHTML = `<p>Error: ${error.message}</p>`;
            });
    }

    // Cause the Enter key to trigger the Lookup button
    countryInput.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            lookupButton.click();
        }
    });
});


