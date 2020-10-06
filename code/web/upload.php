<?php

if (! empty($_FILES)) {
    require_once __DIR__.'/../activation/ActivationService.php';

    $contents = file_get_contents($_FILES['file']['tmp_name']);
    $data = base64_decode($contents);
    $data = json_decode($data, true);
    $serial = $data['serial'];
    $pcHash = $data['pc_hash'];
    $productName = $data['product_name'];
    $dto = new SerialActivationInputDTO($serial, $pcHash, $productName);
    $activationResult = ActivationService::instance()->activateSerial($dto);

    $result = json_encode($activationResult);
    $result = base64_encode($result);
    exit($result);
}
?>

<html lang="ru">
<head>
    <title>Загрузка файла активации</title>
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: -webkit-gradient(linear, left bottom, left top, from(#4F6072), to(#8699AA));
            background: linear-gradient(to top, #4F6072, #8699AA);
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            align-items: center;
        }

        .upload {
            position: relative;
            width: 400px;
            min-height: 445px;
            box-sizing: border-box;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            padding-bottom: 20px;
            background: #fff;
            animation: fadeup .5s .5s ease both;
            transform: translateY(20px);
            opacity: 0;
        }

        .upload .upload-files header {
            background: #4db6ac;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            text-align: center;
        }

        .upload .upload-files header p {
            color: #fff;
            font-size: 40px;
            margin: 0;
            padding: 50px 0;
        }

        .upload .upload-files header p i {
            transform: translateY(20px);
            opacity: 0;
            font-size: 30px;
            animation: fadeup .5s 1s ease both;
        }

        .upload .upload-files header p .up {
            font-weight: bold;
            transform: translateX(-20px);
            display: inline-block;
            opacity: 0;
            animation: faderight .5s 1.5s ease both;
        }

        .upload .upload-files header p .load {
            display: inline-block;
            font-weight: 100;
            margin-left: -8px;
            transform: translateX(-20px);
            opacity: 0;
            animation: faderight 1s 1.5s ease both;
        }

        .upload .upload-files .body {
            text-align: center;
            padding: 50px 0 30px;
        }

        .upload .upload-files .body.hidden {
            display: none;
        }

        .upload .upload-files .body input {
            visibility: hidden;
        }

        .upload .upload-files .body i {
            font-size: 65px;
            color: lightgray;
        }

        .upload .upload-files .body p {
            font-size: 14px;
            padding-top: 15px;
            line-height: 1.4;
        }

        .upload .upload-files .body p b,
        .upload .upload-files .body p a {
            color: #4db6ac;
        }

        .upload .upload-files .body.active {
            border: dashed 2px #4db6ac;
        }

        .upload .upload-files .body.active i {
            box-shadow: 0 0 0 -3px #fff, 0 0 0 lightgray, 0 0 0 -3px #fff, 0 0 0 lightgray;
            animation: file .5s ease both;
        }

        @-webkit-keyframes file {
            50% {
                box-shadow: -8px 8px 0 -3px #fff, -8px 8px 0 lightgray, -8px 8px 0 -3px #fff, -8px 8px 0 lightgray;
            }
            75%,
            100% {
                box-shadow: -8px 8px 0 -3px #fff, -8px 8px 0 lightgray, -16px 16px 0 -3px #fff, -16px 16px 0 lightgray;
            }
        }

        @keyframes file {
            50% {
                box-shadow: -8px 8px 0 -3px #fff, -8px 8px 0 lightgray, -8px 8px 0 -3px #fff, -8px 8px 0 lightgray;
            }
            75%,
            100% {
                box-shadow: -8px 8px 0 -3px #fff, -8px 8px 0 lightgray, -16px 16px 0 -3px #fff, -16px 16px 0 lightgray;
            }
        }

        .upload .upload-files .body.active .pointer-none {
            pointer-events: none;
        }

        .upload .upload-files footer {
            width: 100%;
            margin: 0 auto;
            height: 0;
        }

        .upload .upload-files footer .divider {
            margin: 0 auto;
            width: 0;
            border-top: solid 4px #46aba1;
            text-align: center;
            overflow: hidden;
            transition: width .5s ease;
        }

        .upload .upload-files footer .divider span {
            display: inline-block;
            transform: translateY(-25px);
            font-size: 12px;
            padding-top: 8px;
        }

        .upload .upload-files footer.hasFiles {
            height: auto;
        }

        .upload .upload-files footer.hasFiles .divider {
            width: 100%;
        }

        .upload .upload-files footer.hasFiles .divider span {
            transform: translateY(0);
            transition: -webkit-transform .5s .5s ease;
            transition: transform .5s .5s ease;
            transition: transform .5s .5s ease, -webkit-transform .5s .5s ease;
        }

        .upload .upload-files footer .list-files {
            width: 320px;
            margin: 15px auto 0;
            padding-left: 5px;
            text-align: center;
            overflow-x: hidden;
            overflow-y: auto;
            max-height: 210px;
        }

        .upload .upload-files footer .list-files::-webkit-scrollbar-track {
            background-color: rgba(211, 211, 211, 0.25);
        }

        .upload .upload-files footer .list-files::-webkit-scrollbar {
            width: 4px;
            background-color: rgba(211, 211, 211, 0.25);
        }

        .upload .upload-files footer .list-files::-webkit-scrollbar-thumb {
            background-color: rgba(77, 182, 172, 0.5);
        }

        .upload .upload-files footer .list-files .file {
            width: 300px;
            min-height: 50px;
            display: -webkit-box;
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
            opacity: 0;
            animation: fade .35s ease both;
        }

        .upload .upload-files footer .list-files .file .name {
            font-size: 12px;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            width: 80px;
            text-align: left;
        }

        .upload .upload-files footer .list-files .file .progress {
            width: 175px;
            height: 5px;
            border: solid 1px lightgray;
            border-radius: 2px;
            background: linear-gradient(to left, rgba(77, 182, 172, 0.2), rgba(77, 182, 172, 0.8)) no-repeat;
            background-size: 100% 100%;
        }

        .upload .upload-files footer .list-files .file .progress.active {
            animation: progress 1.5s linear;
        }

        @-webkit-keyframes progress {
            from {
                background-size: 0 100%;
            }
            to {
                background-size: 100% 100%;
            }
        }

        @keyframes progress {
            from {
                background-size: 0 100%;
            }
            to {
                background-size: 100% 100%;
            }
        }

        .upload .upload-files footer .list-files .file .done {
            cursor: pointer;
            width: 40px;
            height: 40px;
            background: #4db6ac;
            border-radius: 50%;
            margin-left: -10px;
            transform: scale(0);
            position: relative;
        }

        .upload .upload-files footer .list-files .file .done:before {
            content: "View";
            position: absolute;
            top: 0;
            left: -5px;
            font-size: 24px;
            opacity: 0;
        }

        .upload .upload-files footer .list-files .file .done:hover:before {
            transition: all .25s ease;
            top: -30px;
            opacity: 1;
        }

        .upload .upload-files footer .list-files .file .done.anim {
            animation: done1 .5s ease forwards;
        }

        .upload .upload-files footer .list-files .file .done.anim #path {
            animation: done2 2.5s .5s ease forwards;
        }

        .upload .upload-files footer .list-files .file .done #path {
            stroke-dashoffset: 7387.59423828125;
            stroke-dasharray: 7387.59423828125 7387.59423828125;
            stroke: #fff;
            fill: transparent;
            stroke-width: 50px;
        }

        .upload .upload-files footer .result {
            display: none;
            font-size: 12px;
            padding: 1em;
            color: #4db6ac;
            text-align: center;
        }

        .upload .upload-files footer .result.visible {
            display: block;
        }

        @-webkit-keyframes done2 {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes done2 {
            to {
                stroke-dashoffset: 0;
            }
        }

        @-webkit-keyframes done1 {
            50% {
                transform: scale(0.5);
                opacity: 1;
            }
            80% {
                transform: scale(0.25);
                opacity: 1;
            }
            100% {
                transform: scale(0.5);
                opacity: 1;
            }
        }

        @keyframes done1 {
            50% {
                transform: scale(0.5);
                opacity: 1;
            }
            80% {
                transform: scale(0.25);
                opacity: 1;
            }
            100% {
                transform: scale(0.5);
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeup {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeup {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @-webkit-keyframes faderight {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes faderight {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @-webkit-keyframes fade {
            to {
                opacity: 1;
            }
        }

        @keyframes fade {
            to {
                opacity: 1;
            }
        }

        @media (max-width: 400px) {
            .upload {
                width: 100%;
                height: 100%;
            }
        }

    </style>
</head>
<body>

<div class="upload">
    <div class="upload-files">
        <header>
            <p>
                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                <span class="up">A</span>
                <span class="load">ctivation</span>
            </p>
        </header>
        <div class="body" id="drop">
            <i class="fa fa-file-text-o pointer-none" aria-hidden="true"></i>
            <p class="pointer-none"><b>Drag</b> the activation file to this area <br/> or <a href=""
                                                                                                 id="triggerFile">click here</a> to browse</p>
            <input type="file" multiple="multiple"/>
        </div>
        <footer>
            <div class="divider">
                <span><AR>EXECUTION</AR></span>
            </div>
            <div class="list-files">
                <!--   template   -->
            </div>
            <div class="result">License file generation.<br>Download will start automatically...</div>
        </footer>
    </div>
</div>

<script>//DOM
    //APP
    let App = {};

    $ = document.querySelector;

    App.init = (function () {
        //Init
        function handleFileSelect(files) {

            //files template
            let template = `${Object.keys(files)
                .map(file => `
    <div class="file file--${file}">
        <div class="name"><span>${files[file].name}</span></div>
        <div class="progress active"></div>
        <div class="done">
        <a href="" target="_blank">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1000 1000">
                <g><path id="path" d="M500,10C229.4,10,10,229.4,10,500c0,270.6,219.4,490,490,490c270.6,0,490-219.4,490-490C990,229.4,770.6,10,500,10z M500,967.7C241.7,967.7,32.3,758.3,32.3,500C32.3,241.7,241.7,32.3,500,32.3c258.3,0,467.7,209.4,467.7,467.7C967.7,758.3,758.3,967.7,500,967.7z M748.4,325L448,623.1L301.6,477.9c-4.4-4.3-11.4-4.3-15.8,0c-4.4,4.3-4.4,11.3,0,15.6l151.2,150c0.5,1.3,1.4,2.6,2.5,3.7c4.4,4.3,11.4,4.3,15.8,0l308.9-306.5c4.4-4.3,4.4-11.3,0-15.6C759.8,320.7,752.7,320.7,748.4,325z"</g>
            </svg>
        </a>
        </div>
    </div>
`)
                .join("")}`;

            document.querySelector("#drop").classList.add("hidden");
            document.querySelector("footer").classList.add("hasFiles");
            setTimeout(() => {
                document.querySelector(".list-files").innerHTML = template;
            }, 1000);

            [...files].forEach((file, i) => {
                sendFile(file)
                    .then((data) => {
                        setTimeout(() => {
                            createAndDownloadFile("licence.txt", data);
                            document.querySelector(".result").classList.add("visible")
                        }, 1000)
                    })
                    .finally(() => {
                        console.log(`.file--${i}`);
                        document.querySelector(`.file--${i}`).querySelector(".progress").classList.remove("active");
                        document.querySelector(`.file--${i}`).querySelector(".done").classList.add("anim");
                    });
            });
        }

        async function sendFile(file) {
            let formData = new FormData();
            formData.append("file", file);
            try {
                let r = await fetch('/web/upload.php', {method: "POST", body: formData});
                console.log('File loaded.');
                return r.text();
            } catch (e) {
                console.log('File loading error...:', e);
                return ""
            }
        }

        function createAndDownloadFile(filename, text) {
            var element = document.createElement('a');
            element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
            element.setAttribute('download', filename);

            element.style.display = 'none';
            document.body.appendChild(element);

            element.click();

            document.body.removeChild(element);
        }

        // trigger input
        document.querySelector("#triggerFile").addEventListener("click", evt => {
            evt.preventDefault();
            document.querySelector("input[type=file]").click();
        });

        // drop events
        document.querySelector("#drop").ondragleave = evt => {
            document.querySelector("#drop").classList.remove("active");
            evt.preventDefault();
        };
        document.querySelector("#drop").ondragover = document.querySelector("#drop").ondragenter = evt => {
            document.querySelector("#drop").classList.add("active");
            evt.preventDefault();
        };
        document.querySelector("#drop").ondrop = evt => {
            document.querySelector("input[type=file]").files = evt.dataTransfer.files;
            document.querySelector("footer").classList.add("hasFiles");
            document.querySelector("#drop").classList.remove("active");
            
            handleFileSelect(evt.dataTransfer.files);

            evt.preventDefault();
        };

        // input change
        document.querySelector("input[type=file]").addEventListener("change", evt => handleFileSelect(evt.target.files));
    })();
</script>
</body>
</html>
