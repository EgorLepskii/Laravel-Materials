window.onload = function () {
    let modal = document.querySelector('#update-link-modal');
    let closeButtons = document.querySelectorAll('#update-link-modal .close-buttons');
    let modalForm = document.querySelector('#update-link-modal form');

    let modalFormNameInput = document.querySelector('#update-link-modal form #floatingModalSignature');
    let modalFormSignInput = document.querySelector('#update-link-modal form #floatingModalLink');


    document.querySelectorAll('.update-links').forEach((elem) => {
        elem.addEventListener('click', async () => {
            modal.style.display = "block";

            let linkId = elem.dataset.linkid;
            let linkSign = elem.dataset.linksign;
            let linkUrl = elem.dataset.linkurl;

            modalFormNameInput.value = linkSign;
            modalFormSignInput.value = linkUrl;

            modalForm.action = window.location.origin + `/link/${linkId}/update`;

            let input = await document.createElement("input");

            input.type = "hidden";
            input.name = "linkId";
            input.value = linkId;

            modalForm.appendChild(input);
        });
    });


    closeButtons.forEach((e) => {
        e.addEventListener('click', () => {
            modal.style.display = "none";
        });
    });

}



