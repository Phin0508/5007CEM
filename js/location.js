function changeImage(imageSrc) {
    document.getElementById('mainImage').src = imageSrc;
}

document.getElementById('addToCart').addEventListener('click', function() {
    const pax = document.getElementById('pax').value;
    alert(`Added ${pax} ticket(s) to cart!`);
    // Here you would typically send this data to a server or update a cart object
});