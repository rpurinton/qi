function favorite(element, type, favorite_id) {
    event.preventDefault();
    if (element.style.color != "rgb(255, 255, 0)") {
        element.style.color = "#ff0";
        element.classList.remove("mdi-star-outline");
        element.classList.add("mdi-star");
        method = 'add';
        fetch('/views/favorites/favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                method: method,
                type: type,
                favorite_id: favorite_id
            })
        });
    }
    else {
        element.style.color = "#fff";
        element.classList.remove("mdi-star");
        element.classList.add("mdi-star-outline");
        method = 'remove';
        if (window.location.href.endsWith("qi.discommand.com/favorites")) {
            element.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement.remove();
        }
        fetch('/views/favorites/favorite.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                method: method,
                type: type,
                favorite_id: favorite_id
            })
        });
    }
}
