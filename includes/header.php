<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
<meta charset="UTF-8">
<title>Soul Store | The Digital Curator</title>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&amp;family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-primary-container": "#b487d7",
                        "secondary-fixed-dim": "#c9bfff",
                        "tertiary-container": "#31303b",
                        "background": "#fcf9f8",
                        "on-background": "#1c1b1b",
                        "secondary": "#5b3cdd",
                        "on-error-container": "#93000a",
                        "on-error": "#ffffff",
                        "inverse-surface": "#313030",
                        "on-tertiary-fixed": "#1b1a25",
                        "on-secondary-fixed": "#1a0063",
                        "surface-container-high": "#eae7e7",
                        "on-secondary-container": "#fffbff",
                        "tertiary-fixed": "#e4e0ef",
                        "secondary-fixed": "#e5deff",
                        "outline": "#7d7483",
                        "on-secondary-fixed-variant": "#441cc8",
                        "on-primary-fixed": "#2e004e",
                        "error-container": "#ffdad6",
                        "on-surface": "#1c1b1b",
                        "surface-container-low": "#f6f3f2",
                        "surface-tint": "#754c97",
                        "surface-container": "#f0eded",
                        "outline-variant": "#cec3d3",
                        "on-primary": "#ffffff",
                        "error": "#ba1a1a",
                        "secondary-container": "#7459f7",
                        "surface-dim": "#dcd9d9",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#4c4451",
                        "surface-container-highest": "#e5e2e1",
                        "inverse-on-surface": "#f3f0ef",
                        "primary-fixed-dim": "#e0b6ff",
                        "surface-bright": "#fcf9f8",
                        "on-primary-fixed-variant": "#5c347d",
                        "on-tertiary-fixed-variant": "#474551",
                        "on-tertiary-container": "#9a97a5",
                        "surface-variant": "#e5e2e1",
                        "primary-fixed": "#f2daff",
                        "surface": "#fcf9f8",
                        "tertiary": "#1c1b26",
                        "surface-container-lowest": "#ffffff",
                        "primary-container": "#451d66",
                        "inverse-primary": "#e0b6ff",
                        "tertiary-fixed-dim": "#c8c5d3",
                        "primary": "#2f0150",
                        "on-tertiary": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fcf9f8;
        }
        h1, h2, h3, .headline {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>
<body class="bg-surface text-on-surface antialiased">
<!-- TopNavBar -->
<nav class="fixed top-0 w-full z-50 bg-[#fcf9f8]/70 dark:bg-[#2f0150]/70 backdrop-blur-md shadow-[0_8px_32px_rgba(47,1,80,0.06)]">
<div class="flex justify-between items-center px-8 py-4 max-w-7xl mx-auto w-full">
<div class="text-2xl font-black text-[#2f0150] dark:text-[#fcf9f8] font-['Manrope'] tracking-tight">Soul Store</div>
<div class="hidden md:flex items-center gap-8 font-['Manrope'] font-bold tracking-tight">
<a class="text-[#5b3cdd] border-b-2 border-[#5b3cdd] pb-1 hover:opacity-100 transition-all scale-95 active:scale-90 duration-200" href="index.php">Home</a>
<a class="text-[#2f0150] dark:text-[#fcf9f8] opacity-80 hover:opacity-100 hover:text-[#5b3cdd] transition-all scale-95 active:scale-90 duration-200" href="cart_view.php">Cart</a>
<?php if (isset($_SESSION['user'])): ?>
<a class="text-[#2f0150] dark:text-[#fcf9f8] opacity-80 hover:opacity-100 hover:text-[#5b3cdd] transition-all scale-95 active:scale-90 duration-200" href="user/profile.php">Profile</a>
<a class="text-[#2f0150] dark:text-[#fcf9f8] opacity-80 hover:opacity-100 hover:text-[#5b3cdd] transition-all scale-95 active:scale-90 duration-200" href="user/logout.php">Logout</a>
<?php else: ?>
<a class="text-[#2f0150] dark:text-[#fcf9f8] opacity-80 hover:opacity-100 hover:text-[#5b3cdd] transition-all scale-95 active:scale-90 duration-200" href="user/login.php">Login</a>
<a class="text-[#2f0150] dark:text-[#fcf9f8] opacity-80 hover:opacity-100 hover:text-[#5b3cdd] transition-all scale-95 active:scale-90 duration-200" href="user/register.php">Register</a>
<?php endif; ?>
<a class="text-[#2f0150] dark:text-[#fcf9f8] opacity-80 hover:opacity-100 hover:text-[#5b3cdd] transition-all scale-95 active:scale-90 duration-200" href="admin/login.php">Admin</a>
</div>
<div class="flex items-center gap-4">
<button class="p-2 text-[#2f0150] dark:text-[#fcf9f8]">
<span class="material-symbols-outlined" data-icon="search">search</span>
</button>
</div>
</div>
</nav>
</body>
</html>
