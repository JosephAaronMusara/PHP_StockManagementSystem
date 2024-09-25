document.addEventListener('DOMContentLoaded', () => {
    const userDetailsForm = document.getElementById('userDetailsForm');
    const themeSelect = document.getElementById('themeSelect');
    const applyThemeButton = document.getElementById('applyThemeButton');

    userDetailsForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const userName = document.getElementById('userName').value;
        const userEmail = document.getElementById('userEmail').value;
        const userPassword = document.getElementById('userPassword').value;

        fetch('/api/updateUserDetails.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                name: userName,
                email: userEmail,
                password: userPassword
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User details updated successfully');
            } else {
                alert('Failed to update user details');
            }
        })
        .catch(error => {
            console.error('Error updating user details:', error);
        });
    });

    // theme
    applyThemeButton.addEventListener('click', function() {
        const selectedTheme = themeSelect.value;
        if (selectedTheme === 'light') {
            document.body.classList.add('light-mode');
            document.body.classList.remove('dark-mode');
        } else if (selectedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            document.body.classList.remove('light-mode');
        }
    });

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        themeSelect.value = savedTheme;
        applyThemeButton.click();
    }

    applyThemeButton.addEventListener('click', function() {
        localStorage.setItem('theme', themeSelect.value);
    });
});
