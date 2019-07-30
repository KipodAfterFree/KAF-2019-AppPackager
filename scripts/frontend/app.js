let layout = document.createElement("div");

function load() {
    welcome();
}

function welcome() {
    hideAll();
    show("welcome");
}

function stageA() {
    hideAll();
    show("stageA");
}

function stageB() {
    hideAll();
    show("stageB");
}

function stageC() {
    hideAll();
    show("stageC");
}

function stageD() {
    hideAll();
    show("stageD");
}

function checkout() {
    hideAll();
    show("checkout");
}

function generate() {
    hideAll();
    setText("Preparing Your Web-App");
    let body = new FormData;
    body.append("create", "true");
    body.append("app-name", get("app-name").value);
    body.append("app-desc", get("app-desc").value);
    body.append("app-color", get("app-color").value);
    body.append("app-icon", get("app-icon-url").value);
    body.append("app-layout", layout.outerHTML);
    fetch("php/builder.php", {
        method: "post",
        body: body
    }).then(response => {
        response.text().then((dataUrl) => {
            if (!dataUrl.startsWith("data:application/zip")) {
                setText(dataUrl);
            } else {
                window.location.href = dataUrl;
                thanks();
            }
        });
    });
}

function addButton() {
    let id = prompt("Button ID");
    let onclick = prompt("Button Onclick");
    let text = prompt("Button Text");
    let button = document.createElement("button");
    button.setAttribute("id", id);
    button.setAttribute("onclick", onclick);
    button.innerText = text;
    layout.appendChild(button);
}

function addInput() {
    let id = prompt("Input ID");
    let placeholder = prompt("Input Placeholder");
    let type = prompt("Input Type");
    let value = prompt("Input Text");
    let input = document.createElement("input");
    input.setAttribute("id", id);
    input.setAttribute("placeholder", placeholder);
    input.setAttribute("type", type);
    input.setAttribute("value", value);
    layout.appendChild(input);
}

function addImage() {
    let id = prompt("Image ID");
    let src = prompt("Image Source");
    let size = prompt("Image Size");
    let alt = prompt("Image Alt");
    let image = document.createElement("img");
    image.setAttribute("id", id);
    image.setAttribute("src", src);
    image.setAttribute("height", size);
    image.setAttribute("width", size);
    image.setAttribute("alt", alt);
    layout.appendChild(image);
}

function addText() {
    let text = prompt("Text Value");
    let size = prompt("Text Size");
    let id = prompt("Text ID");
    let paragraph = document.createElement("p");
    paragraph.setAttribute("id", id);
    if (size !== "") paragraph.style.fontSize = size;
    paragraph.innerText = text;
    layout.appendChild(paragraph);
}

function thanks() {
    hideAll();
    show("thanks");
}

function credits() {
    hideAll();
    show("credits");
}

function setText(text) {
    hideAll();
    show("general-text");
    get("general-text").innerText = text;
}

function hideAll() {
    hide("welcome");
    hide("stageA");
    hide("stageB");
    hide("stageC");
    hide("stageD");
    hide("checkout");
    hide("thanks");
    hide("credits");
    hide("general-text");
}