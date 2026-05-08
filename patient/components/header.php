<?php
// clinic/components/header.php
if (!isset($page_title)) {
    $page_title = $clinic['name'] . ' | Admin Panel';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($page_title); ?></title>
    <?php echo Middleware::csrfMeta(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Flatpickr Datepicker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'] },
                    colors: { 
                        primary: '#0d9488', // Emerald 600
                        secondary: '#14b8a6', // Teal 500
                        accent: '#f0fdfa', // Mint background
                        sidebar: '#ffffff'
                    }
                }
            }
        }
    </script>
    <!-- html2pdf.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        
        /* Hard-Lock Layout to prevent Jitter */
        body { margin: 0; padding: 0; overflow-x: hidden; }
        aside { width: 18rem !important; flex-shrink: 0; }
        main { flex: 1; min-width: 0; }
        .flex.min-h-screen { display: flex; align-items: stretch; }

        /* Smooth Transitions */
        .no-flicker { backface-visibility: hidden; transform: translateZ(0); }

        @media print {
            .no-print, aside, header, .fixed.inset-0.z-\[100\] { position: static !important; display: block !important; background: white !important; }
            .no-print, aside, header, .bg-slate-900\/40, button, .border-t.border-slate-100 { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            main { padding: 0 !important; margin: 0 !important; width: 100% !important; }
            .fixed.inset-0.z-\[100\] { padding: 0 !important; overflow: visible !important; }
            .max-w-4xl { max-width: 100% !important; width: 100% !important; box-shadow: none !important; border: none !important; border-radius: 0 !important; }
            .overflow-hidden { overflow: visible !important; }
            .max-h-\[80vh\] { max-height: none !important; height: auto !important; }
            .print-card { border: 1px solid #e2e8f0 !important; box-shadow: none !important; border-radius: 1rem !important; margin-bottom: 2rem !important; break-inside: avoid; }
            .print-bg-slate { background-color: #f8fafc !important; -webkit-print-color-adjust: exact; }
            .print-text-emerald { color: #059669 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 font-sans antialiased selection:bg-primary selection:text-white">

<!-- Global Page Loader -->
<div id="global-loader" class="fixed inset-0 z-[9999] bg-[#f8fafc] flex items-center justify-center transition-opacity duration-300">
    <div class="w-10 h-10 border-4 border-slate-200 border-t-primary rounded-full animate-spin"></div>
</div>

<script>
    // Hide loader when page is fully loaded
    window.addEventListener('load', function() {
        const loader = document.getElementById('global-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => { loader.style.display = 'none'; }, 300);
        }
    });

    // FIX: Hide loader when navigating back/forward (Bfcache)
    window.addEventListener('pageshow', function(event) {
        const loader = document.getElementById('global-loader');
        if (event.persisted && loader) {
            loader.style.opacity = '0';
            loader.style.display = 'none';
        }
    });

    // Show loader when clicking navigation links to smooth out transitions
    document.addEventListener('DOMContentLoaded', function() {
        const links = document.querySelectorAll('a[href]:not([href^="#"]):not([target="_blank"])');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                // Ignore modifier clicks (new tab, etc)
                if (e.ctrlKey || e.shiftKey || e.metaKey || e.button === 1) return;
                
                const loader = document.getElementById('global-loader');
                if (loader) {
                    loader.style.display = 'flex';
                    // Force reflow for transition
                    void loader.offsetWidth;
                    loader.style.opacity = '1';
                }
            });
        });
    });
</script>

<div class="flex min-h-screen">
