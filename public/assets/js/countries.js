$(document).ready(function() {
    const select = $('#countrySelect');
    
    // Initialize Select2 immediately with a loading message
    select.select2({
        width: '100%',
        minimumResultsForSearch: 5,
        dropdownParent: select.parent(),
        placeholder: 'Loading countries...',
        disabled: true // Disable until data is loaded
    });

    // Load countries
    $.getJSON('/assets/js/countries.json', function(countries) {
        select.empty().append('<option value="">Select a country</option>');
        
        // Convert to array and sort
        const sortedCountries = Object.entries(countries)
            .sort((a, b) => a[1].localeCompare(b[1]));
        
        // Add options to select
        sortedCountries.forEach(([code, name]) => {
            select.append($('<option>', {
                value: code,
                text: name
            }));
        });

        // Re-enable select and update Select2
        select.prop('disabled', false);
        select.trigger('change');
        
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error loading countries:', textStatus, errorThrown);
        select.empty().append('<option value="">Error loading countries</option>');
        select.prop('disabled', false);
        select.trigger('change');
    });
});