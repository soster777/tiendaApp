document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("imagen");
    const preview = document.getElementById("preview");

    let filesArray = [];

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();
        filesArray.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }

    input.addEventListener("change", function() {
        preview.innerHTML = "";
        filesArray = [];

        Array.from(input.files).forEach(file => {

            const mime = file.type;

            const valid = [
                "image/jpeg",
                "image/png",
                "video/mp4",
                "video/quicktime",  
                "video/mpeg",
                "video/x-msvideo"  
            ];

            if (!valid.includes(mime)) return;

            const reader = new FileReader();
            reader.onload = function(e) {

                const div = document.createElement("div");
                div.style.position = "relative";
                div.style.display = "inline-block";
                div.style.margin = "5px";

                if (mime.startsWith("image/")) {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.style.width = "100px";
                    img.style.height = "100px";
                    img.style.objectFit = "cover";
                    img.style.border = "1px solid #ccc";
                    img.style.borderRadius = "5px";
                    div.appendChild(img);
                } else {
                    const video = document.createElement("video");
                    video.src = e.target.result;
                    video.controls = true;
                    video.style.width = "100px";
                    video.style.height = "100px";
                    video.style.objectFit = "cover";
                    video.style.border = "1px solid #ccc";
                    video.style.borderRadius = "5px";
                    div.appendChild(video);
                }

                const btn = document.createElement("button");
                btn.type = "button";
                btn.innerText = "X";
                btn.style.position = "absolute";
                btn.style.top = "0";
                btn.style.right = "0";
                btn.style.background = "red";
                btn.style.color = "white";
                btn.style.border = "none";
                btn.style.borderRadius = "50%";
                btn.style.width = "20px";
                btn.style.height = "20px";
                btn.style.cursor = "pointer";

                btn.addEventListener("click", function() {
                    preview.removeChild(div);
                    filesArray = filesArray.filter(f => f !== file);
                    updateInputFiles();
                });

                div.appendChild(btn);
                preview.appendChild(div);

                filesArray.push(file);
                updateInputFiles();
            };

            reader.readAsDataURL(file);
        });
    });
});
