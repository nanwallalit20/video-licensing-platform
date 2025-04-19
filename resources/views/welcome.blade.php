Frontend Part.
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <link href="https://vjs.zencdn.net/8.16.1/video-js.css" rel="stylesheet"/>
</head>
<body>
{{ $videoUrl }}
<video
    id="my-video"
    class="video-js"
    controls
    preload="auto"
    width="640"
    height="264"
    poster="MY_VIDEO_POSTER.jpg"
    data-setup="{}"
>
    <source src="{{ $videoUrl }}" type='application/x-mpegURL'/>
    <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a
        web browser that
        <a href="https://videojs.com/html5-video-support/" target="_blank"
        >supports HTML5 video</a
        >
    </p>
</video>
<script src="https://vjs.zencdn.net/8.16.1/video.min.js"></script>
</body>
</html>
