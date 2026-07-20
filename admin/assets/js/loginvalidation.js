const form = document.querySelector('.needs-validation');
const usernameInput = document.getElementById('yourUsername');
const passwordInput = document.getElementById('yourPassword');
const errorMessageDiv = document.getElementById('error-message');

form.addEventListener('submit', async (e) => {
  e.preventDefault(); // Prevent default form submission

  // Clear previous errors
  usernameInput.classList.remove('is-invalid');
  passwordInput.classList.remove('is-invalid');
  errorMessageDiv.classList.add('d-none'); // Hide the error message div

  // Check if all fields are filled before submitting
  if (!usernameInput.value || !passwordInput.value) {
    // Show error message if fields are empty
    errorMessageDiv.classList.remove('d-none');
    errorMessageDiv.textContent = 'Please fill in all fields.';

    // Add 'is-invalid' class to empty fields
    if (!usernameInput.value) {
      usernameInput.classList.add('is-invalid');
    }
    if (!passwordInput.value) {
      passwordInput.classList.add('is-invalid');
    }
    return; // Stop further execution
  }

  const formData = new FormData(form);

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (data.status === 'success') {
      // Redirect if login is successful
      window.location.href = data.redirect;
    } else {
      // Show error messages at the top
      errorMessageDiv.classList.remove('d-none');
      errorMessageDiv.textContent = data.message;

      // Add 'is-invalid' class to inputs with errors
     
    }
  } catch (error) {
    console.error('Error:', error);
    errorMessageDiv.classList.remove('d-none');
    errorMessageDiv.textContent = 'Something went wrong. Please try again later.';
  }
}); 