$(document).ready(function() {
    
    
    $.getJSON('/assets/js/countries.json', function(countries) {
      
        const select = $('#countrySelect');
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
        
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error loading countries:', textStatus, errorThrown);
        $('#countrySelect').empty()
            .append('<option value="">Error loading countries</option>');
    });
});