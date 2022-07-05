function sendRequest(url, method, data) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url);
    let csrfToken = document.getElementsByTagName('meta')['csrf-token'].content;
    xhr.setRequestHeader('X-CSRF-TOKEN',csrfToken);
    xhr.send(data);

    return xhr;
}
