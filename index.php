<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "config/db.php"; 
?>
<?php include "includes/header.php"; ?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="Fashion Campaign" class="w-full h-full object-cover" data-alt="High-fashion editorial shot of a model wearing minimalist streetwear against a neutral architectural backdrop with soft afternoon shadows" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_jDa6H67nSMzzCBo0lRBTvGykyC8inPvjESK9rI8e0jFU5veqdsOCLCyToyBEtpSDkgGsZhzuhJZ79hAmiw6ngZRqbcKJc1LVSBjPopjkifmG2qwAKRS5V3LAd1KXdAcFj_L5fPAYVn3ZwZzrumyWPKhC3Letl8fwOBDp9Q8xrPjo4o2vwC8tGHUk_lpD5UKknY-hHaclk3fv5um8jlHVxihivcejQPvKW5rXps5dMFdKzALKYZxDIcXirp64WdXrxiBKnJG4Vhw"/>
<div class="absolute inset-0 bg-gradient-to-r from-background via-background/40 to-transparent"></div>
</div>
<div class="relative z-10 max-w-7xl mx-auto px-8 w-full">
<div class="max-w-2xl">
<span class="text-secondary font-bold tracking-widest text-xs uppercase mb-4 block">New Season 2024</span>
<h1 class="text-6xl md:text-8xl font-extrabold text-primary leading-[1.1] tracking-tighter mb-8">Style That Speaks Your <span class="text-secondary italic">Soul</span></h1>
<p class="text-lg text-on-surface-variant mb-10 max-w-lg leading-relaxed">
                    Curated essentials for the modern spirit. Discover high-end fashion that transcends trends and reflects your inner identity.
                </p>
<div class="flex gap-4">
<button class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-10 py-5 rounded-xl font-bold text-sm tracking-wide shadow-xl transition-transform hover:scale-105 active:scale-95">
                        Shop New Arrivals
                    </button>
<button class="bg-surface-container-highest text-primary px-10 py-5 rounded-xl font-bold text-sm tracking-wide transition-colors hover:bg-surface-container-high">
                        Our Story
                    </button>
</div>
</div>
</div>
</section>

<!-- Special Offer Banner -->
<section class="px-8 py-12">
<div class="max-w-7xl mx-auto">
<div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-r from-[#2f0150] to-[#5b3cdd] p-12 text-white flex flex-col md:flex-row items-center justify-between gap-8">
<div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
<div class="relative z-10">
<span class="inline-block px-4 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-widest mb-4">Special Offer</span>
<h2 class="text-3xl md:text-4xl font-bold mb-2">Get 20% off on your first purchase</h2>
<p class="text-on-primary-container font-medium">Use code: <span class="text-white font-bold tracking-widest">SOUL20</span> at checkout.</p>
</div>
<div class="relative z-10">
<button class="bg-white text-primary px-8 py-4 rounded-xl font-extrabold text-sm hover:bg-surface-bright transition-colors shadow-lg">
                        Shop Now
                    </button>
</div>
</div>
</div>
</section>

<!-- Featured Products Grid -->
<section class="max-w-7xl mx-auto px-8 py-24">
<div class="flex justify-between items-end mb-16">
<div>
<h2 class="text-4xl font-extrabold text-primary tracking-tight mb-4">Featured Collection</h2>
<p class="text-on-surface-variant">The definitive edit of our most loved silhouettes.</p>
</div>
<div class="flex gap-2">
<button class="w-12 h-12 flex items-center justify-center rounded-full bg-surface-container text-primary">
<span class="material-symbols-outlined" data-icon="arrow_back">arrow_back</span>
</button>
<button class="w-12 h-12 flex items-center justify-center rounded-full bg-primary text-on-primary">
<span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
</button>
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
<?php
$products = $conn->query("SELECT * FROM products LIMIT 8");
while ($p = $products->fetch()):
?>
<!-- Product -->
<div class="group relative">
<div class="aspect-[3/4] rounded-xl overflow-hidden mb-6 bg-surface-container-low transition-all duration-500 group-hover:shadow-[0_32px_48px_rgba(47,1,80,0.06)] group-hover:scale-[1.02]">
<img alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover" src="uploads/<?= htmlspecialchars($p['image']) ?>"/>
<div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
<button class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-lg text-primary hover:text-secondary">
<span class="material-symbols-outlined" data-icon="favorite">favorite</span>
</button>
</div>
</div>
<div class="flex justify-between items-start">
<div>
<span class="text-[10px] font-bold text-secondary uppercase tracking-[0.2em] mb-1 block">Stock: <?= $p['stock'] ?></span>
<h3 class="text-lg font-bold text-primary mb-1"><?= htmlspecialchars($p['name']) ?></h3>
<p class="text-xl font-bold text-primary">₹<?= number_format($p['price']) ?></p>
</div>
<a href="cart.php?id=<?= $p['product_id'] ?>" class="bg-primary text-on-primary w-12 h-12 rounded-xl flex items-center justify-center hover:bg-secondary transition-colors">
<span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
</a>
</div>
</div>
<?php endwhile; ?>
</div>
</section>

<?php include "includes/footer.php"; ?>