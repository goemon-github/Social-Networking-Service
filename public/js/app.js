/*
    Send
*/
// JSON形式のデータを送信する関数
async function sendJsonData(url, data) {
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

/*
 TimeLine
*/

//TimeLineのイベントをまとめてリスナー登録する関数 
function bindTimeLineEvents() {
    const TimeLine = document.getElementById('timeline-container');
    if (!TimeLine) {
        return ;
    }

    TimeLine.addEventListener('click', async function (event) {
        const likeBtn = event.target.closest('.likeBtn');;
        if (likeBtn) {
            // handleLikeClick を作成する
            await handleLikeClick(likeBtn);
            return;
        }

        const commentBtn = event.target.closest('.commentBtn');
        if (commentBtn) {
            await handleCommentClick(commentBtn);
        }

    });
    
}

// タイムラインの更新処理
async function refreshTimeline() {
    const res = await fetch('/timeline/items');
    if (!res.ok) {
        alert("タイムラインの更新に失敗しました。");
        return;
    }
    const html = await res.text();

    const container = document.getElementById('timeline-container');
    if(!container){
        return;
    }
    container.innerHTML = html;
    setLikeButtonStyle();
}


/* 
    Post
*/

// 投稿フォームの送信イベントのリスナーを登録する
function bindPostForm() {
    const form = document.getElementById("post-form");
    if(!form) {
        return;
    }
    
    form.addEventListener('submit', handlePostSubmit);
}

// 投稿の送信する
async function submitPostForm(event, url) {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    return sendJsonData(url, formData);
}

// 投稿の処理
async function handlePostSubmit(event) {
    try{
        const result =  await submitPostForm(event, "form/post");
        if(!result || !result.success) {
            alert("投稿の送信に失敗しました。");
            return;
        }

        closePostModal();
        await refreshTimeline();
    }catch(err) {
        alert("投稿の送信に失敗しました。");
    }
}

// 
function setFormAction(path) {
    if (path === 'clear') path = '';
    const postForm = document.getElementById('post-form');
    postForm.setAttribute('action', path);
}

// 投稿モーダルを閉じる
function closePostModal() {
    const el = document.getElementById('crud-modal');
    if(!el) {
        return;
    }

    if(el.contains(document.activeElement)) {
        document.activeElement.blur();
    }

    const flowbite  = new Modal(el);
    flowbite.hide();
}

function openPostModal() {
    const el = document.getElementById('crud-modal');
    if (!el) {
        return;
    }
    const flowbite = new Modal(el);
    flowbite.show();
}


/*  
    Like
*/

// いいねのクリックイベントの処理
async function handleLikeClick(likeBtn) {
    const likeCount = likeBtn.querySelector('.likeCount');
    const postId = getPostId(likeBtn);
    
    const formData = new FormData();
    formData.append("postId", postId);
    formData.append("status", likeBtn.dataset.liked === 'true' ? false : true);

    const res = await sendJsonData('/post/like', formData);

    if(res && res.success){
        likeCount.innerText = res.likeCount;
        const iconPath = likeBtn.querySelector('.likeIcon svg path');
        toggleLikeIcon(likeBtn, iconPath);
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


/* 
    Comment
*/
// コメントのクリックイベントの処理
async function handleCommentClick(commentBtn) {
        const parentPostId = getPostId(commentBtn);
        setParentPostIdCommentForm(parentPostId);
        openPostModal();
}


async function getCommentCount(postId) {
    const formData = new FormData();
    formData.append("postId", postId);
    const res = await sendJsonData('/post/comment/count', formData);
    if(res && res.success){
        return res.comment_count;
    }
     return null;
}

function updateCommentCount(el, count) {
    el.querySelector('.commentCount').innerText = count;
}

// 投稿にpostIDをセットする
function setParentPostIdCommentForm(parentPostId){
    const postForm = document.getElementById('post-form');
    if(!postForm) {
        return;
    }

    let postIdInput = postForm.querySelector('input[name="parent_post_id"]');
    if(!postIdInput) {
        postIdInput = document.createElement('input');
        postIdInput.type = 'hidden';
        postIdInput.name = 'parent_post_id';
        postForm.prepend(postIdInput);
    }
    postIdInput.value = parentPostId;
}
/*
    helpers
 */

// クリックした要素からpostIdを取得する 
function getPostId(el) {
    const targetEl = el.closest("[data-postid]");
    const postId = targetEl.getAttribute("data-postid");
    return postId;
}



async function main() {
    bindPostForm();
    bindTimeLineEvents();
    setLikeButtonStyle();
}

main();