let title_video = {
    ready: function () {
        $(document).on('click', '.add_new_caption', title_video.add_new_caption)
        $(document).on('click', '.add_new_language', title_video.add_new_language)
        $(document).on('click', '.remove_caption_item', title_video.remove_caption_item)
        $(document).on('click', '.remove_language_item', title_video.remove_language_item)
        $(document).on('change', '#sameAsPrimary', title_video.sameAsPrimary)
    },
    add_new_caption: function () {
        let caption_html = $('.caption_fields').html()
        $(this).closest('.row').prev('.caption_container').append(caption_html);
    },
    add_new_language: function () {
        let language_html = $('.language_fields').html()
        $(this).closest('.row').prev('.language_container').append(language_html);
    },
    remove_caption_item: function () {
        $(this).closest('.caption_item').remove()
    },
    remove_language_item: function () {
        $(this).closest('.language_item').remove()
    },
    sameAsPrimary: function () {
        console.log($(this).is(':checked'))
        if ($(this).is(':checked')) {
            $(this).closest('.card').find('.card-body').hide()
        } else {
            $(this).closest('.card').find('.card-body').show()
        }

    }
}
$(document).ready(function () {
    title_video.ready()
})

var chapterId = $('#chapters_count').data('chapter-count');
var titleId = $('#title_id').val();
var uploadRoute = $('#season-data-container').data('upload-url');
const mediaTypes = $('#data-container').data('media-types');
const storagePath = $('#data-container').data('storage-path');
const seasonNumber = $('#seasonLinkList').data('season-number');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Global counter to generate unique IDs
var uniqueCastId = 0;

function addCast() {
    uniqueCastId++;
    var template = $("#cast-template")[0];
    var newCastFragment = $(template.content.cloneNode(true));
    var castRow = newCastFragment.find('.cast-row').first();
    castRow.attr('id', 'cast-row-' + uniqueCastId);

    newCastFragment.find('.remove-cast').first().on('click', function () {
        removeCast(castRow.attr('id'));
    });
    $("#cast-list").append(newCastFragment);
}

function removeCast(rowId) {
    $('#' + rowId).remove();
}

function switchTab(e) {
    let form = $(e.form['0'])
    form.closest('.title_edit_form_container').find('li > a.active').closest('li').next('li').find('a').tab('show');
}

$(document).on('click', '.back_tab', function (e) {
    $('.title_edit_form_container').find('li > a.active').closest('li').prev('li').find('a').tab('show');
});


function addChapter() {
    chapterId++;
    const $chapterTemplate = $("#chapter-template");
    const $chapterList = $("#chapter-list");

    // Clone the template
    const $newChapterRow = $chapterTemplate.clone();
    $newChapterRow.attr("id", `chapter-row-${chapterId}`);
    $newChapterRow.removeClass("hidden"); // Remove the hidden class to make it visible

    // Update the remove button's onclick handler
    $newChapterRow.find(".remove-chapter").attr("onclick", `removeChapter('chapter-row-${chapterId}')`);

    // Append the new chapter row to the list
    $chapterList.append($newChapterRow);
}

function removeChapter(chapterId) {
    const $chapterRow = $(`#${chapterId}`);
    if ($chapterRow.length) {
        $chapterRow.remove(); // Remove the row with the given ID
    }
}

$(document).ready(function () {
    $('.select2-dropdown').each(function () {
        let $this = $(this);
        let url = $this.data('url'); // Get the URL from the data attribute
        let placeholder = $this.data('placeholder') ?? "Select an option";

        if (url) {
            $this.select2({
                placeholder: placeholder,
                allowClear: true,
                ajax: {
                    url: url,
                    dataType: 'json',
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                            pagination: {
                                more: data.next_page_url ? true : false
                            }
                        };
                    }
                },
                tags: $this.data('tags') ? true : false, // Enable tags if specified
                tokenSeparators: [','], // Allow comma and space-separated tags
            });
        } else {
            $this.select2({
                placeholder: placeholder,
                allowClear: true,
                tags: $this.data('tags') ? true : false,
                minimumResultsForSearch: Infinity,
                tokenSeparators: [',', ' '] // Allow comma and space-separated tags
            });
        }
    });
});


$(document).ready(function () {

    let tabCount = typeof seasonNumber !== 'undefined' ? seasonNumber - 1 : 0;
    $('#add_season').click(function () {
        tabCount++;
        const tabId = `season-tab-${tabCount}`;
        const contentId = `season-content-${tabCount}`;

        // Clone and configure the tab template
        const tabTemplate = document.querySelector('#season-tab-template').content.cloneNode(true);
        const tabLink = tabTemplate.querySelector('a');
        tabLink.id = tabId;
        tabLink.href = `#${contentId}`;
        tabLink.textContent = `Season ${tabCount}`;
        tabLink.setAttribute('aria-controls', contentId);
        if (tabCount === 1) {
            tabLink.classList.add('active');
            tabLink.setAttribute('aria-selected', 'true');
        }
        $('#season-tabs').append(tabTemplate);

        // Clone and configure the content template
        const contentTemplate = document.querySelector('#season-content-template').content.cloneNode(true);
        const contentPane = contentTemplate.querySelector('.tab-pane');
        contentPane.id = contentId;
        contentPane.setAttribute('aria-labelledby', tabId);
        if (tabCount === 1) {
            contentPane.classList.add('show', 'active');
        }

        $('#season-content').append(contentTemplate);

        // Activate the new tab
        $(`#${tabId}`).tab('show');

    });

    function collectSeasonData() {
        const seasons = [];

        // Loop through each season
        $('.season_custom_class .tab-content .tab-pane').each(function () {
            const season = {
                season_id: $(this).find('#season_id').val(),
                season_name: $(this).find('#season_name').val(),
                synopsis: $(this).find('#synopsis').val(),
                release_date: $(this).find('#release_date').val(),
                season_image: $(this).find('[name="uploaded_season_image_portrait"]').val(),
                season_image_landscape: $(this).find('[name="uploaded_season_image_landscape"]').val(),
                season_trailer: $(this).find('[name="season_uploaded_trailer"]').val(),
                episodes: []
            };
            // Loop through episodes in the current season
            $(this).find('.accordion-item').each(function () {
                const episode = {
                    episode_id: $(this).find('#episode_id').val(),
                    episode_name: $(this).find('#episode-name').val(),
                    synopsis: $(this).find('#episode-synopsis').val(),
                    release_date: $(this).find('#episode-release-date').val(),
                    main_video: $(this).find('[name="episode_uploaded_main_video"]').val(),
                    caption:[],
                    dubbed_language:[]
                };

                // Get all caption items
                $(this).find('.caption_container .caption_item').each(function() {
                    const captionItem = {
                        text: $(this).find('input[name="uploaded_caption_text[]"]').val(),
                        file: $(this).find('input[name="uploaded_caption[]"]').val()
                    };
                    episode.caption.push(captionItem);
                });

                // Get all language items
                $(this).find('.language_container .language_item').each(function() {
                    const languageItem = {
                        text: $(this).find('input[name="uploaded_language_text[]"]').val(),
                        file: $(this).find('input[name="uploaded_language[]"]').val()
                    };
                    episode.dubbed_language.push(languageItem);
                });

                season.episodes.push(episode);
            });

            seasons.push(season);
        });

        return seasons;
    }

    $(document).on('click', '#save-proress-season', function () {
        // Get the URL from the button's data attribute
        let url = $(this).data('season_route');
        $('.loader').show()

        // Send the combined season data via AJAX
        $.ajax({
            url: url,
            type: 'POST',
            data: JSON.stringify({seasons: collectSeasonData(), title_id: titleId}), // Wrap the array in an object and convert it to a JSON string
            contentType: 'application/json', // Set the content type to JSON
            success: function (response) {
                if (response.success) {
                    justify.customJustify('success', response.message);
                    pageReload();
                } else {
                    justify.customJustify('error', response.message);
                }
                $('.loader').hide()
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessages = '<ul><li>' + Object.values(errors).map(error => error[0]).join('</li><li>') + '</li></ul>';
                    justify.customJustify('error', errorMessages);

                } else {
                    justify.customJustify('error', 'Something went wrong. Please try again.');
                }
                $('.loader').hide()
            }
        });

    });


    $(document).on('click', '.delete_season_btn button', function () {
        // Get the parent tab pane
        const tabPane = $(this).closest('.tab-pane');
        const tabContentId = tabPane.attr('id');
        const deletedTab = $('#season-tabs a[href="#' + tabContentId + '"]').parent();
        const deleteBtn = tabPane.find('.delete_season_btn button');
        const seasonDeleteUrl = deleteBtn.data('season_delete_url');
        removeEpisodeOrSeason(seasonDeleteUrl, function (response) {
            if (response) {
                justify.customJustify('success', 'Season deleted successfully');
                $('#' + tabContentId).remove();
                deletedTab.remove();
                updateTabIds();
                $('.loader').hide()
            } else {
                justify.customJustify('error', 'Failed to delete season');
                $('.loader').hide()
                return;
            }
        });
    });

    function updateTabIds() {
        let newIndex = 1;
        $('#season-tabs .nav-item').each(function () {
            // Update the tab ID and href
            let tabId = `season-tab-${newIndex}`;
            let tabContentId = `season-content-${newIndex}`;

            $(this).find('a')
                .attr('id', tabId)
                .attr('href', `#${tabContentId}`)
                .attr('aria-controls', tabContentId);

            // Update aria-selected for the active state
            if (newIndex === 1) {
                $(this).find('a').addClass('active').attr('aria-selected', 'true');
            } else {
                $(this).find('a').removeClass('active').attr('aria-selected', 'false');
            }

            // Update the content pane ID and related attributes
            let tabContent = $(`#season-content .tab-pane`).eq(newIndex - 1);
            tabContent.attr('id', tabContentId).attr('aria-labelledby', tabId);

            if (newIndex === 1) {
                tabContent.addClass('show active');
            } else {
                tabContent.removeClass('show active');
            }

            newIndex++;
        });

        // Update tabCount to reflect the new total number of tabs
        tabCount = newIndex - 1;
    }

    // Add Episode dynamically
    $(document).on('click', '.add_episode_button button', function () {
        const accordion = $(this).closest('.custom-episode-accordion').find('.accordion');
        const episodeTemplate = $('#episode-template').html();
        const itemCount = accordion.children('.accordion-item').length + 1;

        // Create new episode block
        const episodeClone = $(episodeTemplate);

        // Update IDs and Labels for the new episode
        episodeClone.find('.accordion-header button')
            .attr('data-bs-target', `#collapse${itemCount}`)
            .attr('aria-controls', `collapse${itemCount}`)
            .text(`Episode ${itemCount}`);
        episodeClone.find('.accordion-collapse')
            .attr('id', `collapse${itemCount}`);

        // Append to accordion and open the new episode
        accordion.append(episodeClone);
        accordion.find(`#collapse${itemCount}`).addClass('show');
    });

    // Delete Episode
    $(document).on('click', '.delete_episode_btn button', function () {
        const pane = $(this).closest('.accordion-item');
        const deleteBtn = pane.find('.delete_episode_btn button');
        const deleteEpisodeUrl = deleteBtn.data('episode_delete_url');
        removeEpisodeOrSeason(deleteEpisodeUrl, function (response) {
            if (response) {
                pane.remove();
                reorderEpisodes();
                justify.customJustify('success', 'Episode deleted successfully.');
                $('.loader').hide()
            } else {
                justify.customJustify('error', 'Some error while deleting episode.');
                $('.loader').hide()
                return;
            }
        });
    });

    function removeEpisodeOrSeason(url, callback) {
        if (confirm("Are you sure you want to remove this?")) {
            // Send AJAX request to remove the media from the database
            $('.loader').show()
            if (typeof url == 'undefined') {
                callback(true)
            }
            $.post(
                url,
                function (response) {
                    if (response.success) {
                        callback(true);

                    } else {
                        callback(false);
                    }
                }
            ).fail(function (xhr, status, error) {
                console.log("An error occurred while removing the media: " + error);
            });
        }
    }

    // Reorder remaining episodes
    function reorderEpisodes() {
        $('.accordion-item').each(function (index) {
            const episodeIndex = index + 1;
            const episode = $(this);

            episode.find('.accordion-header button')
                .attr('data-bs-target', `#collapse${episodeIndex}`)
                .attr('aria-controls', `collapse${episodeIndex}`)
                .text(`Episode ${episodeIndex}`);
            episode.find('.accordion-collapse')
                .attr('id', `collapse${episodeIndex}`);
        });
    }
})




