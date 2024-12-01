function decodeHtml(html) {
    let txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

function createMobileAside() {

    if (window.innerWidth <= 650) {
        if (document.getElementById('mobileAside') !== null) return;

        const backIconButton = document.createElement('i');
        backIconButton.className = 'fa fa-arrow-left';
        backIconButton.addEventListener('click', () => {
            document.querySelector('.messagePage_aside').style = 'transition = left 1s ease-in-out;';
        });

        document.querySelector('.messagePage_asideHeader').prepend(backIconButton);

        const aside = document.createElement('aside');
        aside.id = 'mobileAside';

        const button = document.createElement('button');
        button.textContent = 'Show Message Menu';
        button.addEventListener('click', () => {
            document.querySelector('.messagePage_aside').style.display = 'flex';
        })
        aside.appendChild(button);
        document.querySelector('.messagePage').appendChild(aside);

    } else {
        const aside = document.getElementById('mobileAside');
        const arrow = document.querySelector('.fa-arrow-left');
        if (aside) {
            aside.remove();
        }
        if (arrow) {
            arrow.remove();
        }
    }

}

function sendMessage(event) {
    const token = document.querySelector('.messages_as_buyer').value;
    let textareaValue = document.querySelector('textarea').value;
    textareaValue = textareaValue.trim();

    if (textareaValue === '' || textareaValue === ' ') {
        return;
    }
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_sendMessage.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');


    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let serverResponse = JSON.parse(xhr.response)

            if (typeof Object.values(serverResponse)[0] === "boolean") {
                showPopUp(serverResponse);
            } else {
                document.querySelector('textarea').value = '';

                let newMessage = document.createElement('div');
                newMessage.className = 'current_user_chat';

                let newSpan = document.createElement('span');
                newSpan.className = 'chat_content';
                newSpan.innerText = textareaValue;

                let newTimeStamp = document.createElement('p');
                newTimeStamp.className = 'chat_timestamp';
                newTimeStamp.innerText = new Date().toISOString().slice(0, 19).replace('T', ' ');

                newMessage.appendChild(newSpan);
                newMessage.appendChild(newTimeStamp);
                document.querySelector('.chats > .top').appendChild(newMessage);
                scrollToBottom();
            }


        } else {
            showPopUp({'Error while sending message, please try again later.': false})
        }
    };

    const data = 'content=' + encodeURIComponent(textareaValue) + '&chatID=' + encodeURIComponent(event.target.id)
        + '&itemID=' + encodeURIComponent(event.target.value) + '&token=' + token;
    xhr.send(data);
}


function showPopUp(responseData) {
    let blockerDiv = document.createElement('div');
    blockerDiv.className = 'blockerDiv';
    blockerDiv.addEventListener('click', () => {
        return false
    });

    document.querySelector('body').appendChild(blockerDiv);

    let popupDiv = document.createElement('div');

    popupDiv.className = 'popup';
    popupDiv.id = 'popup';

    let popupContentDiv = document.createElement('div');
    popupContentDiv.className = 'popup-content';

    let closeButtonSpan = document.createElement('span');
    closeButtonSpan.className = 'close';
    closeButtonSpan.id = 'close';
    closeButtonSpan.innerHTML = '&times;';

    let messageParagraph = document.createElement('p');
    messageParagraph.textContent = '';

    popupContentDiv.appendChild(closeButtonSpan);
    popupContentDiv.appendChild(messageParagraph);
    popupDiv.appendChild(popupContentDiv);

    let popup = document.getElementById('popup');
    popup.style.display = 'block';
    popup.querySelector('p').innerText = Object.keys(responseData)[0].toString();

    document.getElementById('close').addEventListener('click', function () {
        document.getElementById('popup').style.display = 'none';
        document.body.removeChild(document.querySelector('.blockerDiv'));
        popup.querySelector('p').innerText = '';
    });
}

function clearChatDiv(event) {
    const chatBox = document.querySelector('.chats');
    document.querySelector('.chats').innerText = '';
    const bottomBar = document.createElement('div');
    bottomBar.className = "sendMessageBar";
    const textAreaElement = document.createElement('textarea');
    textAreaElement.className = "textArea_sendMessageBar";
    textAreaElement.addEventListener('keypress', function (keyBoardEvent) {
        if (keyBoardEvent.key === 'Enter') {
            keyBoardEvent.preventDefault();
            document.querySelector('.button_sendMessageBar').click();
        }
    });
    const sendButton = document.createElement('button');
    sendButton.className = "button_sendMessageBar";
    sendButton.innerText = 'Send Message';
    sendButton.id = event.target.id;
    sendButton.value = event.target.value;
    sendButton.addEventListener('click', sendMessage);
    bottomBar.appendChild(textAreaElement);
    bottomBar.appendChild(sendButton);
    const divElementBottom = document.createElement('div');
    const divElementTop = document.createElement('div');
    divElementBottom.className = "bottom";
    divElementTop.className = "top";
    divElementBottom.appendChild(bottomBar);
    chatBox.appendChild(divElementTop);
    chatBox.appendChild(divElementBottom);
}

function clearAside() {
    document.querySelector('.messagePage_aside').querySelector('aside > ul').innerText = '';
}

function drawChatMessages(serverResponse) {
    document.querySelector('.chats').style = ''
    const chats = document.querySelector('.chats > .top');
    serverResponse.forEach((chat) => {
        let divElement = document.createElement('div');

        if (chat['sender'] === chat['currentUser']) {
            divElement.className = 'current_user_chat';
        } else {
            divElement.className = 'other_user_chat';
        }

        let contentSpan = document.createElement('span');
        contentSpan.className = 'chat_content';
        contentSpan.innerText = decodeHtml(chat['content']);

        let timeParagraph = document.createElement('p');
        timeParagraph.className = 'chat_timestamp';
        timeParagraph.innerText = chat['timestamp'];

        divElement.appendChild(contentSpan);
        divElement.appendChild(timeParagraph);
        chats.appendChild(divElement);
        scrollToBottom();
    })
}


function pollMessages() {
    let sendMessageButton = document.querySelector('.button_sendMessageBar');
    if (sendMessageButton != null) {
        let currentNumberOfMessages = document.querySelectorAll('.chats > .top > .other_user_chat').length;
        currentNumberOfMessages += document.querySelectorAll('.chats > .top > .current_user_chat').length;
        let chatId = sendMessageButton.id
        let itemID = sendMessageButton.value
        let nonce = document.querySelector('.messages_as_seller').value.toString();
        let xhr_r = new XMLHttpRequest();

        xhr_r.open('POST', '/actions/action_pollMessages.php', true);
        xhr_r.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr_r.onload = function () {
            if (xhr_r.status >= 200 && xhr_r.status < 300) {
                let serverResponse = JSON.parse(xhr_r.response)
                if (Object.values(serverResponse)[0]) {
                    if (Object.keys(serverResponse)[0].toString() === 'new') {
                        drawChatMessages(Object.values(serverResponse)[1]);
                        currentNumberOfMessages = document.querySelectorAll('.chats > .top > .other_user_chat').length;
                        currentNumberOfMessages += document.querySelectorAll('.chats > .top > .current_user_chat').length;
                    }
                } else {
                    showPopUp(serverResponse);
                }
            } else {
                showPopUp({'Failed to obtain messages': false})
            }
        };
        const data = 'chatID=' + encodeURIComponent(chatId) + '&itemID=' + encodeURIComponent(itemID)
            + '&token=' + encodeURIComponent(nonce) + '&currentNumberOfMessages='
            + encodeURIComponent(currentNumberOfMessages);
        xhr_r.send(data);
    }

}

function fetchChatMessages(event) {
    clearChatDiv(event);
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_getMessages.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let serverResponse = JSON.parse(xhr.response)
            if (typeof Object.values(serverResponse)[0] === "boolean") {
                showPopUp(serverResponse);
            } else {
                drawChatMessages(serverResponse);
            }
        } else {
            showPopUp({'Failed to obtain messages': false})
        }
    };

    const data = 'type=' + encodeURIComponent('fetch_messages') + '&chatID=' + encodeURIComponent(event.target.id)
        + '&itemID=' + encodeURIComponent(event.target.value) + '&token=' + encodeURIComponent(event.target.nonce);
    xhr.send(data);
}


function fetchMessagesAsSeller(event) {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_getMessages.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {

            let serverResponse = JSON.parse(xhr.response)

            if (typeof Object.values(serverResponse)[0] === "boolean") {
                showPopUp(serverResponse);
            } else {
                clearAside();
                event.target.style = 'background-color: rgb(156, 214, 248);'
                document.querySelector('.messages_as_buyer').style = 'background-color: #6c757d;';
                const ulElement = document.querySelector('aside > ul');
                Object.values(serverResponse).forEach((message) => {
                    let liElement = document.createElement("li");
                    liElement.id = message['chatID'];
                    liElement.nonce = document.querySelector('.messages_as_seller').value.toString();
                    liElement.value = message['item'];
                    liElement.innerText = message['model'];
                    liElement.addEventListener('click', fetchChatMessages);
                    ulElement.appendChild(liElement);
                });
            }

        } else {
            showPopUp({'Failed to obtain messages': false})
        }
    };

    const data = 'type=' + encodeURIComponent('as_seller') + '&token=' + encodeURIComponent(event.target.value);
    xhr.send(data);
}

function fetchMessagesAsBuyer(event) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/actions/action_getMessages.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            let serverResponse = JSON.parse(xhr.response)

            if (typeof Object.values(serverResponse)[0] === "boolean") {
                showPopUp(serverResponse);
            } else {
                clearAside();
                event.target.style = 'background-color: rgb(156, 214, 248);'
                document.querySelector('.messages_as_seller').style = 'background-color: #6c757d;';
                const ulElement = document.querySelector('aside > ul');
                Object.values(serverResponse).forEach((message) => {
                    let liElement = document.createElement("li");
                    liElement.id = message['chatID'];
                    liElement.nonce = document.querySelector('.messages_as_buyer').value.toString();
                    liElement.value = message['item'];
                    liElement.innerText = message['model'];
                    liElement.addEventListener('click', fetchChatMessages);
                    ulElement.appendChild(liElement);
                });
            }

        } else {
            showPopUp({'Failed to obtain messages': false})
        }
    }

    const data = 'type=' + encodeURIComponent('as_buyer') + '&token=' + encodeURIComponent(event.target.value);
    xhr.send(data);
}


function scrollToBottom() {
    const chats = document.querySelector('.chats > .top');
    chats.scrollTop = chats.scrollHeight;
}

const select_messages_as_seller = document.querySelector('.messages_as_seller');
const select_messages_as_buyer = document.querySelector('.messages_as_buyer');
select_messages_as_seller.addEventListener('click', fetchMessagesAsSeller);
select_messages_as_buyer.addEventListener('click', fetchMessagesAsBuyer);
window.setInterval(pollMessages, 4000);
window.addEventListener('resize', createMobileAside);
window.addEventListener('DOMContentLoaded', createMobileAside);
document.querySelector('.chats').style = 'justify-content: center; color: white;'

document.addEventListener("DOMContentLoaded", function () {
    let messageTab = document.querySelector('a[data-tab="messages"]');
    if (messageTab) {
        messageTab.classList.add("active");
    }
});