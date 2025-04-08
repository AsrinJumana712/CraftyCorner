<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraftyCorner</title>
    <link rel="stylesheet" href="../bootstrap/dist/css/bootstrap.css" />
    <script src="../bootstrap/dist/js/bootstrap.bundle.js"></script>
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body>
    <section class="hero">
        <h1>Welcome to CraftyCorner</h1>
        <p>Locally crafted, naturally beautiful. From our hands to your heart.</p>
        <a href="auth.php" class="btn">Shop Now</a>
    </section>

    <section class="container my-5">
        <h2 class="text-center" mb-4>Explore Our Items</h2>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="product-card p-3">
                    <img src="./uploads/candles.png" class="product-image" alt="" />
                    <div class="card-body">
                        <h5 class="card-title">Candles</h5>
                        <p class="card-text">Aromatherapy & decor in one.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="product-card p-3">
                    <img src="./uploads/soap.png" class="product-image" alt="" />
                    <div class="card-body">
                        <h5 class="card-title">Soaps</h5>
                        <p class="card-text">Natural, gentle and beautifully crafted.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="product-card p-3">
                    <img src="./uploads/handcrafts.png" class="product-image" alt="" />
                    <div class="card-body">
                        <h5 class="card-title">Crafts & Decor</h5>
                        <p class="card-text">Warm up your space with handmade charm.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container my-5 text-center">
        <h2 class="mb-4">Why Choose Crafty Corner?</h2>
        <div class="row">
            <div class="col-md-4">
                <i class="fas fa-leaf fa-2x text-success mb-2"></i>
                <h5>Eco-Friendly</h5>
                <p>We use sustainable materials and packaging to support the planet.</p>
            </div>

            <div class="col-md-4">
                <i class="fas fa-hand-holding-heart fa-2x text-danger mb-2"></i>
                <h5>Handmade with Love</h5>
                <p>WEvery item is crafted with attention, care, and a personal touch.</p>
            </div>

            <div class="col-md-4">
                <i class="fas fa-shipping-fast fa-2x text-primary mb-2"></i>
                <h5>Fast Delivery</h5>
                <p>Get your orders quickly with our trusted delivery partners.</p>
            </div>
        </div>
    </section>

    <section class="container my-5">
        <div class="row align-items-center">
            <div class="col-md-6 slide-left">
                <img src="./uploads/common.png" alt="" class="img-fluid rounded shadow" />
            </div>
            <div class="col-md-6 slide-right">
                <h3>Our Story</h3>
                <p>We believe in quality over quantity. Every product is handcrafted in small batches using natural
                    ingredients and eco-friendly materials. Whether it's a candle, a bar of soap, or a décor piece, it’s
                    made with love and intention.</p>
                <a href="index.php" class="btn mt-3">Learn more..</a>
            </div>
        </div>
    </section>

    <section class="newsletter container my-5">
        <h2 class="mb-3">Subscribe to our newsletter</h2>
        <p>Be the first to know about new arrivals and special offers!</p>
        <form action="index.php" class="row g-3 justify-content-center">
            <div class="col-md-6">
                <input type="email" class="form-control" placeholder="Enter your email" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn">Subscribe</button>
            </div>
        </form>
    </section>

    <div class="footer">
        <div class="social-icons mb-2">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-pinterest"></i></a>
        </div>
        <p>&copy; 2025 Crafty Corner. All Rights Reserved.</p>
    </div>
</body>

</html>