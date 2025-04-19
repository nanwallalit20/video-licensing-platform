let shaka_player = {
    ready: function () {
        shaka_player.load_all_player()
    },
    load_all_player: async function () {
        // first check if player supported
        let is_support = await shaka_player.init_player_config()

        if (!is_support) {
            return;
        }

        $('.player_container').each(function () {
            let video_id = $(this).find('video').attr('id')
            if (video_id) {
                shaka_player.init_player(video_id)
            }
        })
    },
    init_player_config: async function () {
        // Install built-in polyfills to patch browser incompatibilities.
        shaka.polyfill.installAll();
        // Check to see if the browser supports the basic APIs Shaka needs.
        return !!shaka.Player.isBrowserSupported();
    },
    init_player: async function (video_id) {
        let video = document.getElementById(video_id);
        let manifestUri = $('#' + video_id).data('url')
        let player = new shaka.Player();

        await player.attach(video);
        // Listen for error events.
        player.addEventListener('error', function (event) {
            let error = event.detail
            console.error('Error code', error.code, 'object', error);
        });

        player.configure({
            drm: {
                servers: {
                    'com.widevine.alpha': AWS_LICENCE_WIDEVINE_URL,
                    'com.microsoft.playready': AWS_LICENCE_PLAYREADY_URL
                }
            }
        });

        try {
            await player.load(manifestUri);
        } catch (error) {
            console.error('Error code', error.code, 'object', error);
        }
    }
}

$(document).ready(function () {
    shaka_player.ready();
})
