<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow">
            <div class="card-body">
                <h1 class="card-title text-center">Contact Us</h1>
                <p class="text-center">Got any questions, suggestions, or feedback? Just contact us!</p>
                <form id="contactForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" onclick="submitForm()">Send Message</button>
                    </div>
                </form>
                <!-- Placeholder for the success message -->
                <div id="successMessage" class="alert alert-success d-none alert-dismissible fade show mt-4" role="alert">
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    Your message has been sent successfully!
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function submitForm() {
        // Assume form validation passed
        document.getElementById('successMessage').classList.remove('d-none');

        // Optionally, clear the form fields
        document.getElementById('contactForm').reset();

        // Prevent form from submitting to the server
        return false;
    }
</script>