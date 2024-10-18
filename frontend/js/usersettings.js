    document.addEventListener('DOMContentLoaded', () => {
        const userDetailsForm = document.getElementById('userDetailsForm');
        const themeSelect = document.getElementById('themeSelect');
        const applyThemeButton = document.getElementById('applyThemeButton');
    
        applyThemeButton.addEventListener('click', function() {
            const selectedTheme = themeSelect.value;
            applyTheme(selectedTheme);
            localStorage.setItem('theme', selectedTheme);
        });
    
        function applyTheme(theme) {
            if (theme === 'light') {
                document.body.classList.add('light-mode');
                document.body.classList.remove('dark-mode');
            } else if (theme === 'dark') {
                document.body.classList.add('dark-mode');
                document.body.classList.remove('light-mode');
            }
        }
    
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            themeSelect.value = savedTheme;
            applyTheme(savedTheme);
        } else {
            const userId = localStorage.getItem('loggedInUserId');
            axios.get(`http://localhost/StockManagementSystem/api/endpoints/user.php?action=get&id=${userId}`)
                .then(response => {
                    const user = response.data;
                    const serverSavedTheme = user.theme;
                    if (serverSavedTheme) {
                        localStorage.setItem('theme', serverSavedTheme);
                        themeSelect.value = serverSavedTheme;
                        applyTheme(serverSavedTheme);
                    }
                })
                .catch(error => {
                    console.error('Error fetching user theme:', error);
                });
        }
    
        //user details form
        userDetailsForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const userFormData = new FormData(this);
            const userId = localStorage.getItem('loggedInUserId');
            userFormData.append('theme', themeSelect.value);
    
            const data = Object.fromEntries(userFormData.entries());
    
            axios.put(`http://localhost/StockManagementSystem/api/endpoints/user.php?action=update&id=${userId}`, data, {
                headers: { 'Content-Type': 'application/json' },
            })
            .then(response => {
                if (response.data.success) {
                    alert('User details updated successfully');
                    localStorage.setItem('theme', themeSelect.value);
                } else {
                    alert('Failed to update user details');
                }
            })
            .catch(error => {
                console.error('Error updating user details:', error);
            });
        });
    });