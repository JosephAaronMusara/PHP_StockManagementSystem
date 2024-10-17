document.addEventListener('DOMContentLoaded', () => {
    const userDetailsForm = document.getElementById('userDetailsForm');
    const themeSelect = document.getElementById('themeSelect');
    const applyThemeButton = document.getElementById('applyThemeButton');

    userDetailsForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const userFormData = new FormData(this);
        const userId = localStorage.getItem('loggedInUserId');

        const data = Object.fromEntries(userFormData.entries());


        axios.put(`http://localhost/StockManagementSystem/api/endpoints/user.php?action=update&id=${userId}`,data, {
            headers: { 
                'Content-Type': 'application/json'
            },
        })
        .then(response => {
            if (response.data.success) {
                alert('User details updated successfully');
            } else {
                alert('Failed to update user details');
            }
        })
        .catch(error => {
            console.error('Error updating user details:', error);
        });
    });

    // Theme
    applyThemeButton.addEventListener('click', function() {
        const selectedTheme = themeSelect.value;
        if (selectedTheme === 'light') {
            document.body.classList.add('light-mode');
            document.body.classList.remove('dark-mode');
        } else if (selectedTheme === 'dark') {
            document.body.classList.add('dark-mode');
            document.body.classList.remove('light-mode');
        }
        localStorage.setItem('theme', selectedTheme);
    });

    // Load and apply saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        themeSelect.value = savedTheme;
        applyThemeButton.click();
    }
});
