<div>
    <style>
        .carousel-item {
            position: relative;
            height: 90vh;
            /* Menyesuaikan tinggi sesuai dengan tinggi layar */
        }

        .carousel-item img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-height: 100%;
            max-width: 100%;
            height: auto;
        }

        @media only screen and (max-width: 767px) {
            .smaller-image {
                width: 50%;
                /* Ubah nilai sesuai kebutuhan Anda */
            }
        }

        @media only screen and (orientation: landscape) {
            .smaller-image {
                width: 35%;
                /* Ubah nilai sesuai kebutuhan Anda */
            }
        }
    </style>
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('img/directselling.jpg') }}" class="img-fluid responsive rounded-top rounded-bottom shadow shadow-custom smaller-image" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/directselling-panel.jpg') }}" class="img-fluid responsive rounded-top rounded-bottom shadow shadow-custom smaller-image" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('img/dinasty.jpg') }}" class="img-fluid responsive rounded-top rounded-bottom shadow shadow-custom smaller-image" alt="...">
            </div>
        </div>
    </div>
</div>