
async function sendData(url, data) {
    try {
        const res = await fetch(url, {
            method: "POST",
            body: data
        });
        if (!res.ok) {
            throw new Error(res.status);
        }

        const contentType = res.headers.get("content-type");
        if (contentType && contentType.includes("application/json")) {
            return  await res.json();
        } else {
            throw new Error(`HTTP error: ${res.status}`);
        }
    } catch (err) {
        console.log("Error:", err);
        return null;
    }
}

async function handleSubmit(url) {
    return new Promise((resolve, reject) => {
        document.getElementById("post-form").addEventListener('submit', async function (event) {
            try {
                event.preventDefault();
                const formData = new FormData(this);
                const result = await sendData(url, formData);
                resolve(result);
            } catch (err) {
                reject(err);
            }
        });
    });
}

async function postClickAction() {
    try {
        const result = await handleSubmit('form/post');
    } catch (err) {
        alert('投稿に失敗しました');
    }
}

// いいねのカウント処理
async function processLikeCount() {
    const likes = document.querySelectorAll('.likeBtn');
    for (const btn of likes){
        btn.addEventListener('click', async function (event) {
            let likeCount = event.currentTarget.querySelector('.likeCount');
            const postId = getPostId(this);

            const formData = new FormData();
            formData.append("postId", postId);

            if (btn.dataset.liked == 'true') {
                formData.append('status', false);
            } else {
                formData.append("status", true);
            } 

            const res = await sendData('/post/like', formData);
            console.log(res);
            if ("likeCount" in res && res.success){
                const count = res['likeCount'];
                likeCount.innerText = count;
            } else if(res.error){
                alert(res.error);
            };


            const iconPath = this.querySelector('.likeIcon svg path');
            toggleLikeIcon(btn, iconPath);
        })
    } 
}


// いいねをすでにクリックしているアイコンの色を変える
function setLikeButtonStyle() {
    const likes = document.querySelectorAll('.likeBtn');
    for (const btn of likes) { 
        if (btn.dataset.liked == 'true') {
            const icon = btn.querySelector('.likeIcon svg path');
            icon.classList.add('like-red');
        }
    }

}

// いいねのアイコンの色を変える
function toggleLikeIcon(btn, el) {
    if (btn.dataset.liked === 'true' &&  el.classList.contains('like-red')) {
        el.classList.remove('like-red');
        el.classList.add('like-outline');
        btn.dataset.liked = 'false';
    }else {
        el.classList.add('like-red');
        el.classList.remove('like-outline');
        btn.dataset.liked = 'true';
    }
}

function getPostId(el) {
    const targetEl = el.closest("[data-postid]");
    const postId = targetEl.getAttribute("data-postid");
    return postId;
}

function setPostIdToPostModal(postId){
    const postForm = document.getElementById('post-form');
    const inputHtml = `<input type="hidden" name="post_id" value="${postId}"></input>`
    postForm.insertAdjacentHTML('afterbegin', inputHtml);
}

function setFormAction(path) {
    if (path === 'clear') path = '';
    const postForm = document.getElementById('post-form');
    postForm.setAttribute('action', path);
}

async function handleCommentClick() {
    const commentBtn = document.querySelectorAll('.commentBtn');
    for (const btn of commentBtn ) {
        btn.addEventListener('click', async function () {
            postId = getPostId(this);
            setPostIdToPostModal(postId);
            const result = await handleSubmit('post/comment');
            this.querySelector('.commentCount').innerText = result['comment_count'];
            console.log('modal close');
            closePostModal();
        })        
    }
}

function closePostModal() {
    const el = document.getElementById('crud-modal');
    const flowbite  = new Modal(el);
    flowbite.hide();
}

async function main() {
    await processLikeCount();
    handleCommentClick();
    postClickAction();
    setLikeButtonStyle();
}

main();