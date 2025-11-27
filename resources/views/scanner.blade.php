<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ticket Scanner</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="margin: 0px;background: black;" >
<div id="video-container">
    <video style="width: 100%;height: 100vh;" id="qr-video"></video>
</div>

<span id="cam-qr-result">None</span>

<script type="module">
    import QrScanner from "./qr-scanner.min.js";
    var a_req=true;
    const video = document.getElementById('qr-video');
    const camQrResult = document.getElementById('cam-qr-result');
    
    function setResult(label, result) {
        // label.textContent = result.data;
        // label.style.color = 'teal';
        if(!a_req){ return ''; }
        a_req=false;
        scanner.stop();
        swal.fire({
            html: '<h5>Loading...</h5>',
            showConfirmButton: false,
            onRender: function() {
                 $('.swal2-content').prepend(sweet_loader);
            }
        });
        $.get('{{ route('ticketscan') }}',{result:result.data}).then((res)=>{
            console.log(res)
            a_req=true;
            if(res.ticket == 0){
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ticket Not Found!",
                    footer: ''
                }).then(()=>{ scanner.start() });
            }
            if(res.ticket == 1){
                Swal.fire({
                    icon: "success",
                    title: "OK",
                    text: "Ticket Verified!",
                    footer: ''
                }).then(()=>{ scanner.start() });
            }
            if(res.ticket == 2){
                Swal.fire({
                    icon: "warning",
                    title: "alert",
                    text: "Ticket already Verified! "+res.msg,
                    footer: ''
                }).then(()=>{ scanner.start() });
            }
            // scanner.start();
            
        });
    }
    const scanner = new QrScanner(video, result => setResult(camQrResult, result), {
        onDecodeError: error => {
            camQrResult.textContent = error;
            camQrResult.style.color = 'inherit';
        },
        highlightScanRegion: true,
        highlightCodeOutline: true,
    });

    
    scanner.start().then(() => {
        QrScanner.listCameras(true).then(cameras => cameras.forEach(camera => {
            // alert(camera);
            // const option = document.createElement('option');
            // option.value = camera.id;
            // option.text = camera.label;
            // camList.add(option);
            scanner.setCamera(camera.id)
        }));
    });

    window.scanner = scanner;
    scanner.start();
    
</script>

</body>
</html>
