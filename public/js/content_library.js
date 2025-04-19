const contentLibraryPageRoute = $('#content-data-container').data('content_library_page_route');
const fetchTitleByIdsRoute = $('#content-data-container').data('fetchtitlebyids_route');
const updateSelectedTitlesRoute = $('#content-data-container').data('updateselectedtitles_route');
const titleType = $('#content-data-container').data('title-type');
const toggleCartItemRoute = $('#content-data-container').data('toggle-cart-item-route');
const cartItemsRoute = $('#content-data-container').data('cart-items-route');
const cartItems = $('#content-data-container').data('cart-items');
const cartItemComponentRoute = $('#content-data-container').data('cart-item-component-route');
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
var selectedTitles = cartItems || [];
let cartItemTemplate = '';

function updateCartItems(data) {
    selectedTitles = data.param.cartItems;
    updateCartButtons();
    toggleViewCartButton();
}

function updateCartButtons() {
    $('.add-to-cart-btn').each(function() {
        const btnTitleId = parseInt($(this).data('id'));
        const btnSeasonId = $(this).data('season-id') || null;
        const button = $(this);
        const isInCart = selectedTitles.some(item =>
            item.titleId == btnTitleId &&
            item.seasonId == btnSeasonId
        );
        const icon = button.find('i');
        if (isInCart) {
            icon.removeClass('fa-cart-plus').addClass('fa-trash');
            button.removeClass('btn-secondary').addClass('btn-danger');
        } else {
            icon.removeClass('fa-trash').addClass('fa-cart-plus');
            button.removeClass('btn-danger').addClass('btn-secondary');
        }
    });
}

// Function to toggle the "View Cart" button visibility
function toggleViewCartButton() {
    if (selectedTitles.length > 0) {
        $('#viewCartBtn').show(); // Show the View Cart button
    } else {
        $('#viewCartBtn').hide(); // Hide the View Cart button
    }
}


$(document).ready(function () {
      function toggleResetButton() {
        const activeFilters = $('.dropdown-filter .close-icon:visible').length;
        if (activeFilters > 0) {
          $('.dropdown-filter #resetAllFilters').show();
        } else {
          $('.dropdown-filter #resetAllFilters').hide();
        }
      }
      $('.dropdown-filter .dropdown-menu .dropdown-item').on('click', function (e) {
        e.preventDefault();
        var $dropdownButton = $(this).closest('.dropdown').find('.dropdown-btn');
        var $buttonText = $dropdownButton.find('.button-text');
        var $closeIcon = $dropdownButton.find('.close-icon');
        var selectedValue = $(this).data('value');
        $buttonText.text(selectedValue);
        $closeIcon.show();
        $dropdownButton.addClass('selected');
        toggleResetButton();
      });
      $('.dropdown-filter .close-icon').on('click', function (e) {
        e.stopPropagation();
        var $dropdownButton = $(this).closest('.dropdown-btn');
        var $buttonText = $dropdownButton.find('.button-text');
        var defaultText = $dropdownButton.data('default');
        $buttonText.text(defaultText);
        $(this).hide();
        $dropdownButton.removeClass('selected');
        toggleResetButton();
      });
      $('.dropdown-filter #resetAllFilters').on('click', function () {
        $('.dropdown-filter .dropdown-btn').each(function () {
          var $dropdownButton = $(this);
          var $buttonText = $dropdownButton.find('.button-text');
          var $closeIcon = $dropdownButton.find('.close-icon');
          var defaultText = $dropdownButton.data('default');
          $buttonText.text(defaultText);
          $closeIcon.hide();
          $dropdownButton.removeClass('selected');
        });
        $(this).hide();
      });


      $('.custom-select-dropdown .dropdown-item').on('click', function() {
        var selectedText = $(this).text();
        $('.select-text').text(selectedText);
        $('.select-dropdown-btn').addClass('selected');
      });
    });

    $(document).ready(function () {


    // Fetch filtered data (no changes here)
    function fetchFilteredData() {
        let filters = {
            title_name: $('#title_name_filter').val(),
        };

        // Add dropdown filters
        $('.dropdown-filter .dropdown-btn.selected').each(function() {
            let filterType = $(this).data('default').toLowerCase();
            let selectedItem = $(this).closest('.dropdown').find('.dropdown-item.active');
            if (selectedItem.length) {
                filters[filterType] = selectedItem.data('value');
            }
        });

        $.ajax({
            url: contentLibraryPageRoute,
            type: 'GET',
            data:filters,
            success: function (response) {
                $('tbody').html(response.html);
                updateCartButtons();
                toggleViewCartButton();
            },
            error: function () {
                alert('Error fetching data.');
            }
        });
    }

    // Update dropdown item click handler
    $('.dropdown-filter .dropdown-item').on('click', function(e) {
        e.preventDefault();
        var $dropdownButton = $(this).closest('.dropdown').find('.dropdown-btn');
        var $buttonText = $dropdownButton.find('.button-text');
        var $closeIcon = $dropdownButton.find('.close-icon');

        // Remove active class from other items in this dropdown
        $(this).closest('.dropdown-menu').find('.dropdown-item').removeClass('active');
        // Add active class to clicked item
        $(this).addClass('active');

        $buttonText.text($(this).text());
        $closeIcon.show();
        $dropdownButton.addClass('selected');

        fetchFilteredData();
    });

     // Reset filters
    $('.dropdown-filter .close-icon').on('click', function(e) {
        e.stopPropagation();
        var $dropdownButton = $(this).closest('.dropdown-btn');
        var $buttonText = $dropdownButton.find('.button-text');
        var defaultText = $dropdownButton.data('default');

        $buttonText.text(defaultText);
        $(this).hide();
        $dropdownButton.removeClass('selected');

        fetchFilteredData();
    });

    // Reset all filters
    $('#resetAllFilters').on('click', function() {
        $('.dropdown-filter .dropdown-btn').each(function() {
            var $dropdownButton = $(this);
            var $buttonText = $dropdownButton.find('.button-text');
            var $closeIcon = $dropdownButton.find('.close-icon');
            var defaultText = $dropdownButton.data('default');

            $buttonText.text(defaultText);
            $closeIcon.hide();
            $dropdownButton.removeClass('selected');
        });

        fetchFilteredData();
    });

    // Page load handler with updateCartButtons
    $(document).ready(function() {
        updateCartButtons();
        toggleViewCartButton();
        $.get(cartItemComponentRoute, function(template) {
            cartItemTemplate = template;
        });
    });


    // Trigger on search input
    $('#title_name_filter').on('keyup', function () {
        fetchFilteredData();
    });

    // On clicking the View Cart button, fetch selected titles data from the server
    $('#viewCartBtn').on('click', function () {
        $('#loader').show();
        $.get(fetchTitleByIdsRoute, function (response) {
            let cartItemsList = $('#cartItemsList');
            cartItemsList.empty();
            cartItemsList.html(response);
            if (response.length > 0) {
                $('#requestBtn').prop('disabled', false);
            } else {
                $('#requestBtn').prop('disabled', true);
            }
            $('#viewCartModal').modal('show');
            $('#loader').hide();
        }).fail(function () {
            $('#loader').hide();
            justify.customJustify('error', 'Error fetching cart titles.');
        });
    });

    // Handle Request button click
    $('#requestBtn').on('click', function () {
        $('#loader').show();
        $.post(updateSelectedTitlesRoute,function (response) {
            $('#viewCartModal').modal('hide');
            $('#loader').hide();
            justify.customJustify('success', response.message);
        })
        .fail(function () {
            $('#loader').hide();
            justify.customJustify('error', 'Error saving data.');
        });
    });
});
