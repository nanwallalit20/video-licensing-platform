$(document).ready(() => {
    var year = new Date().getFullYear();
    $('#currentYear').text(year);
});

const rearrangeTabs = () => {
    // Reorder the remaining tabs and update the labels
    $('#tab-list .nav-item').each(function(index) {
        let tabLink = $(this).find('a');
        let newTabId = 'Session' + (index + 1);
        let newTabName = 'Session ' + (index + 1);
        let newTabContentId = 'Session-content-' + (index + 1);
        tabLink.attr('id', newTabId + '-tab')
               .attr('href', '#' + newTabContentId)
               .text(newTabName)
               .attr('aria-controls', newTabContentId);
        $('#' + tabLink.attr('aria-controls')).attr('id', newTabContentId);
    });
}

let tabCount = 0;

$('#add_season').click(function() {
    // Validate the current tab before adding a new one
    console.log(tabCount);
    if (tabCount > 0) {
        let currentSeasonName = $('#Session-content-' + tabCount + ' #season_name_' + tabCount).val().trim();
        console.log(currentSeasonName);
        if (currentSeasonName === "") {
            alert("Please enter the season name for the current season before adding a new one.");
            return; // Stop the function if validation fails
        }
    }

    tabCount++;
    let tabId = 'Session' + tabCount;
    let tabName = 'Season ' + tabCount;
    let tabContentId = 'Session-content-' + tabCount;

    // Create the new tab link
    let newTab = `
        <li class="nav-item" role="presentation">
        <a class="nav-link ${tabCount === 1 ? 'active' : ''}" id="${tabId}-tab" data-bs-toggle="tab" href="#${tabContentId}" role="tab" aria-controls="${tabContentId}" aria-selected="true">
            ${tabName}
        </a>
        </li>
    `;
    // Create the new tab content
    let newTabContent = `
    <div class="tab-pane fade ${tabCount === 1 ? 'show active' : ''}" id="${tabContentId}" role="tabpanel" aria-labelledby="${tabId}-tab">
        <div class="session_tab_container mt-4">
            <div class="form-group">
                <label for="season_name_${tabCount}">Season Name</label>
                <input type="text" class="form-control" id="season_name_${tabCount}" placeholder="Season Name">
            </div>
            <div class="form-group">
                <label for="synopsis_name">Synopsis</label>
                <textarea class="form-control" id="Synopsis" placeholder="Synopsis" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="release_date">Original Release Date</label>
                <input type="date" id="release-date" name="release_date" class="form-control">
            </div>
            <div class="form-group">
                <label for="season_image">Season Image</label>
                <input type="file" id="image" name="season_image" class="form-control">
            </div>
        </div>
        <div class="delete_season_btn mt-4">
            <button type="button" class="btn btn-danger">Delete Season</button>
        </div>
        <div class="custom-episode-accordion">
            <div class="custom_accordion_block mb-2">
                <div id="accordionExample" class="accordion"></div>
            </div>
            <div class="add_episode_button">
                <button id="addAccordionItem" type="button" class="border-0 fw-bold w-100 p-2 text-start rounded-2">
                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Episode
                </button>
            </div>
        </div>
    </div>
  `;
  $('.custom_tab_container #tab-list').append(newTab);
  $('.custom_tab_container #tab-content').append(newTabContent);

  $('#tab-list a[href="#' + tabContentId + '"]').tab('show');

  $('#season_name_' + tabCount).on('input', function() {
      let newSeasonName = $(this).val().trim();
      let updatedTabName = newSeasonName ? newSeasonName : 'Season ' + tabCount;
      $('#' + tabId + '-tab').text(updatedTabName);
  });
});

$(document).on('click', '.delete_season_btn button', function() {
    let tabContentId = $(this).parents('.tab-pane').attr('id');
    let deletedTab = $('#tab-list a[href="#' + tabContentId + '"]').parent();

    // Remove the deleted tab and content
    $('#' + tabContentId).remove();
    deletedTab.remove();

    tabCount--;
    // After deletion, update the IDs and names of the remaining tabs
    updateTabIds();
});


function updateTabIds() {
    let newIndex = 1;
    $('.custom_tab_container .nav-item').each(function() {
        let tabId = 'Session' + newIndex;
        let tabContentId = 'Session-content-' + newIndex;
        let tabName = 'Season ' + newIndex;
        $(this).find('a')
            .attr('id', tabId + '-tab')
            .attr('href', '#' + tabContentId);
        let tabContent = $(this).parents('.custom_tab_container').find('.tab-content')
            .find('.tab-pane')
            .eq(newIndex - 1);
        tabContent
            .attr('id', tabContentId)
            .removeClass('show active')
            .addClass(newIndex === 1 ? 'show active' : '');
        $(this).find('a')
            .removeClass('active')
            .addClass(newIndex === 1 ? 'active' : '');

        tabContent.find('input, textarea').each(function() {
            let elementId = $(this).attr('id');
            if (elementId) {
                $(this).attr('id', elementId.replace(/\d+/, newIndex));
            }
        });

        newIndex++;
    });
}




$(document).on('click', '.add_episode_button #addAccordionItem', function () {
    const accordion = $(this).closest('.custom-episode-accordion').find('.accordion');
    let itemCount = accordion.children('.accordion-item').length;

    if (itemCount > 0) {
        let currentEpisodeName = $('#collapse' + itemCount + ' #episode_name_' + itemCount).val().trim();
        if (currentEpisodeName === "") {
            alert("Please enter the season name for the current season before adding a new one.");
            return; // Stop the function if validation fails
        }
    }

    itemCount++;

    const newItemId = `accordionItem${itemCount}`;
    const newItem = `
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading${itemCount}">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${itemCount}" aria-expanded="false" aria-controls="collapse${itemCount}">
                    Episode ${itemCount}
                </button>
            </h2>
            <div id="collapse${itemCount}" class="accordion-collapse collapse" aria-labelledby="heading${itemCount}" data-bs-parent="#accordionExample">
                <div class="accordion-body custom-accordion-section">
                    <div class="custom-accordion-block">
                        <div class="form-group">
                            <label for="episode_name_${itemCount}">Episode Name</label>
                            <input type="text" class="form-control" id="episode_name_${itemCount}" placeholder="Episode Name">
                        </div>
                        <div class="form-group">
                            <label for="synopsis_name">Synopsis</label>
                            <textarea class="form-control" id="Synopsis" placeholder="Synopsis" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="release_date">Original Release Date</label>
                            <input type="date" id="release-date" name="release_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="season_image">Episode Image</label>
                            <input type="file" id="image" name="season_image" class="form-control">
                        </div>
                    </div>
                    <div class="video_section">
                        <label for="season_image">Main Video</label>
                        <input type="file" id="image" name="season_image" class="form-control">
                    </div>
                    <div class="caption_section">
                        <label for="season_image">Captions</label>
                        <input type="file" id="image" name="season_image" class="form-control">
                    </div>
                    <div class="delete_episode_btn mt-4">
                        <button type="button" class="btn btn-danger">Delete Episode</button>
                    </div>
                </div>
            </div>
            </div>
        </div>
    `;

    accordion.append(newItem);
    accordion.find(`#collapse${itemCount}`).collapse('show');
});
$(document).on('click', '.delete_episode_btn button', function () {
    const episodeItem = $(this).closest('.accordion-item');
    const parentAccordion = episodeItem.parent('.accordion');
    const nextEpisode = episodeItem.next('.accordion-item').find('.accordion-button');
    const prevEpisode = episodeItem.prev('.accordion-item').find('.accordion-button');
    episodeItem.remove();

    if (nextEpisode.length) {
        nextEpisode.click();
    } else if (prevEpisode.length) {
        prevEpisode.click();
    }
    updateItem();
});
function updateItem() {
    let itemIndex = 1;

    $('.custom-episode-accordion .accordion-item').each(function () {
        let episodeItem = $(this);
        let newItemId = `accordionItem${itemIndex}`;
        let newCollapseId = `collapse${itemIndex}`;
        let newHeadingId = `heading${itemIndex}`;
        let newEpisodeName = `Episode ${itemIndex}`;

        episodeItem.find('.accordion-header button')
            .attr('id', newHeadingId)
            .attr('data-bs-target', `#${newCollapseId}`)
            .text(newEpisodeName);
        episodeItem.find('.accordion-collapse')
            .attr('id', newCollapseId)
            .attr('aria-labelledby', newHeadingId);
        episodeItem.find('input[type="text"]').attr('id', `episode_name_${itemIndex}`);


        episodeItem.find('textarea').attr('id', `synopsis_${itemIndex}`);
        episodeItem.find('input[type="date"]').attr('id', `release_date_${itemIndex}`);
        episodeItem.find('input[type="file"]').each(function() {
            let elementId = $(this).attr('id');
            if (elementId) {
                $(this).attr('id', elementId.replace(/\d+/, itemIndex)); // Update file input ID
            }
        });

        episodeItem.find('.delete_episode_btn button').attr('data-episode', itemIndex);

        itemIndex++;
    });
}





