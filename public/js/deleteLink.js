document.querySelectorAll('.delete-link').forEach(function (e){
    e.addEventListener('click', function (){
        let status = confirm('Подтвердите удаление');

        if (!status) return;
        let materialId = e.dataset.material;
        let linkId = e.dataset.link;

        let uri = "/link/destroy/" +  linkId;
        let redirectUri = "/material/" + materialId;

        let xhr = sendRequest(window.location.origin + uri,'delete');

        xhr.onreadystatechange = function ()
        {
            if (xhr.status === 200)
            {
                window.location.replace(window.location.origin+redirectUri);
            }
        }
    });
});
