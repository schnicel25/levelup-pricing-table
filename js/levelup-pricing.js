document.addEventListener("DOMContentLoaded", function(){

// Get the add feature button
var addFeatureBtn = document.querySelector('.add');

// Add click event listener to the add feature button
addFeatureBtn.addEventListener('click', function() {
    // Create a new div element
    var newDiv = document.createElement('div');

    // Create a new input element
    var newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'levelup_pricing_table_features[]';
    newInput.size = '25';

    // Create a new remove button element
    var newRemoveBtn = document.createElement('button');
    newRemoveBtn.type = 'button';
    newRemoveBtn.classList.add('remove');
    newRemoveBtn.innerHTML = 'Remove';

    // Append the new input and remove button to the new div
    newDiv.appendChild(newInput);
    newDiv.appendChild(newRemoveBtn);

    // Append the new div to the pricing table features container
    var pricingTableFeaturesContainer = document.querySelector('.levelup-pricing-table-features');
    pricingTableFeaturesContainer.appendChild(newDiv);
});

// Get the pricing table features container
var pricingTableFeaturesContainer = document.querySelector('.levelup-pricing-table-features');

// Add feature function
function addFeature(event) {
    // Get the parent element (the container div) of the clicked button
    var parent = event.target.parentNode;

    // Create a new div element
    var newDiv = document.createElement('div');

    // Create a new input element
    var newInput = document.createElement('input');
    newInput.type = 'text';
    newInput.name = 'levelup_pricing_table_features[]';
    newInput.size = '25';

    // Create a new remove button element
    var newRemoveBtn = document.createElement('button');
    newRemoveBtn.type = 'button';
    newRemoveBtn.classList.add('remove');
    newRemoveBtn.innerHTML = 'Remove';

    // Append the new input and remove button to the new div
    newDiv.appendChild(newInput);
    newDiv.appendChild(newRemoveBtn);

    // Append the new div to the pricing table features container
    parent.appendChild(newDiv);
}

// Remove feature function
function removeFeature(event) {
    // Remove the parent node (the div container) of the clicked button
    event.target.parentNode.remove();
}

// Add event listeners for the existing column's buttons
var addFeatureButtons = document.getElementsByClassName("add");
for (var i = 0; i < addFeatureButtons.length; i++) {
    addFeatureButtons[i].addEventListener("click", addFeature);
}

var removeFeatureButtons = document.getElementsByClassName("remove");
for (var i = 0; i < removeFeatureButtons.length; i++) {
    removeFeatureButtons[i].addEventListener("click", removeFeature);
}

// Add click event listener to the pricing table features container
pricingTableFeaturesContainer.addEventListener('click', function(event) {
    // Check if the clicked element is a remove button
    if (event.target.matches('.remove')) {
        // Remove the parent node (the div container) of the clicked button
        event.target.parentNode.remove();
    }
});

/* Add new column */

function addNewColumn() {
    // Get the parent element where the new column will be added
    var parent = document.getElementById("levelup-pricing-table-columns");
    
    // Create a new div element for the new column
    var newColumn = document.createElement("div");
    newColumn.classList.add("levelup-pricing-table-column");
    
    // Add all the fields from the first column to the new column
    var firstColumn = document.getElementsByClassName("levelup-pricing-table-column")[0];
    var fields = firstColumn.children;
    for (var i = 0; i < fields.length; i++) {
        var newField = fields[i].cloneNode(true);
        newField.value = "";
        newColumn.appendChild(newField);
    }
    
    // Append the new column to the parent element
    parent.appendChild(newColumn);

        // Add event listeners for the new column's buttons
    var addFeatureButtons = newColumn.getElementsByClassName("add");
    for (var i = 0; i < addFeatureButtons.length; i++) {
        addFeatureButtons[i].addEventListener("click", addFeature);
    }

    var removeFeatureButtons = newColumn.getElementsByClassName("remove");
    for (var i = 0; i < removeFeatureButtons.length; i++) {
        removeFeatureButtons[i].addEventListener("click", removeFeature);
    }
}

// Add event listener to the "New column +" button
var addColumnButton = document.getElementById("add-column-button");
addColumnButton.addEventListener("click", addNewColumn);

// Disable save function

  document.getElementById("add-column-button").addEventListener("click", function(event){
    event.preventDefault();
});

});