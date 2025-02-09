async function sendData(url, data) {
    try {
        const res = await fetch(url, {
            method: "POST",
            body: data
        });
        if (!res.ok) {
            throw new Error(res.status);
        }
        const resData = await res.json();
        return resData;
    } catch (err) {
        console.log("Error:", err);
        return null;
    }
}

async function processLikeCount() {
    const likes = document.querySelectorAll('.likeBtn');
    for (const btn of likes){
        btn.addEventListener('click', async function () {

            let likeCount = this.querySelector('.likeCount');
            const parentDiv = this.closest("[data-postid]");
            const postId = parentDiv.getAttribute("data-postid");

            const formData = new FormData();
            formData.append("postId", postId);

            let status = false;
            if (btn.classList.contains('like')) {
                formData.append("status", true);
                status = true;
            } else {
                formData.append('status', false);
                status = false;
            } 

            const res = await sendData('/post/like', formData);

            if ("likeCount" in res && res.success){
                const count = res['likeCount'];
                likeCount.innerText = count;
            } else if(res.error){
                alert(res.error);
            };

            if (status) {
                btn.classList.remove('like');
                btn.classList.add('unlike');
            } else {
                btn.classList.remove('unlike');
                btn.classList.add('like');
            }

            const iconPath = document.querySelector('.likeIcon svg path');
            toggleLikeIcon(iconPath);
        })
    } 
    

}

function toggleLikeIcon(el) {
    if (el.classList.contains('like-red')) {
        el.classList.remove('like-red');
        el.classList.add('like-outline');
    }else {
        el.classList.add('like-red');
        el.classList.remove('like-outline');
    }
}

async function main() {
    await processLikeCount();
}

main();