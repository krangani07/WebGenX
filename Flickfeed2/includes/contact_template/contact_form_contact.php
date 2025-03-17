<!-- /Flickfeed2/includes/contact_template/contact_form_contact.php -->
<?php
/**
 * Contact Form Section - Contact Page
 *
 * This file contains the contact form section for the contact page.
 */
?>
<section class="py-16 bg-white">
    <div class="container mx-auto px-6">
        <div class="lg:w-2/3 mx-auto">
            <h2 class="text-3xl font-bold text-dark mb-8 text-center">
                Send us a message
            </h2>
            <form class="space-y-6">
                <div>
                    <label for="name" class="block mb-2 text-gray-700">Your Name</label>
                    <input type="text" id="name" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                </div>
                <div>
                    <label for="email" class="block mb-2 text-gray-700">Your Email</label>
                    <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required>
                </div>
                <div>
                    <label for="message" class="block mb-2 text-gray-700">Message</label>
                    <textarea id="message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-primary focus:border-primary" required></textarea>
                </div>
                <div>
                    <button type="submit" class="py-3 px-6 bg-primary text-white font-semibold rounded-md hover:bg-sky-600 transition-colors duration-200 w-full">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>