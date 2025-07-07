function logout() {
    localStorage.removeItem('customItineraryLocations');
 
    window.location.href = 'clear-booking-session.php?redirect_to=../login/logout.php';
}