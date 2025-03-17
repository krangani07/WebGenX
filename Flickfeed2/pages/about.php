<!-- /Flickfeed2/pages/about.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flickfeed2 - About</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style type="text/tailwindcss">
        @layer utilities {
            .content-visibility-auto {
                content-visibility: auto;
            }
            .content-visibility-hidden {
                content-visibility: hidden;
            }
            .content-visibility-visible {
                content-visibility: visible;
            }
        }
        @layer base {
            body {
                @apply font-lato;
            }
        }
    </style>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#03a9f4',
                        secondary: '#f44336',
                        dark: '#1e293b',
                    },
                    fontFamily: {
                        lato: ['Lato', 'sans-serif'],
                    },
                },
            },
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <?php include '../includes/header.php'; ?>

    <!-- Hero Section - About Page -->
    <?php include '../includes/about_template/hero_about.php'; ?>

    <!-- About Us Section -->
    <?php include '../includes/about_template/about_us_section_about.php'; ?>

    <!-- Team Section -->
    <?php include '../includes/about_template/team_section_about.php'; ?>

    <!-- Skills Section -->
    <?php include '../includes/about_template/skills_section_about.php'; ?>

    <!-- Call to Action Section -->
    <?php include '../includes/about_template/cta_about.php'; ?>

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Global JavaScript -->
    <script src="../assets/js/global.js"></script>
    <!-- About Page JavaScript -->
    <script src="../assets/js/about.js"></script>
</body>
</html>