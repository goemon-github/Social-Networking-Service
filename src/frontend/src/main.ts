// src/frontend/main.ts

import './styles/tailwind.css';
import { Modal } from 'flowbite';
//import 'flowbite/dist/flowbite.css';


async function sendData(url: string, data: FormData): Promise<any | null> {
    try {
        const res = await fetch(url, {
            method: "POST",
            body: data
        });
        if (!res.ok) {
            throw new Error(res.status.toString());
        }
        return await res.json();
    } catch (err) {
        console.error("Error:", err);
        return null;
    }
}

async function handleSubmit(url: string): Promise<any> {
    return new Promise((resolve, reject) => {
        const form = document.getElementById("post-form") as HTMLFormElement;
        form.addEventListener('submit', async function (event: Event) {
            event.preventDefault();
            const formData = new FormData(form);
            try {
                const result = await sendData(url, formData);
                resolve(result);
            } catch (err) {
                reject(err);
            }
        }, { once: true }); // prevent multiple bindings
    });
}

async function postClickAction(): Promise<void> {
    try {
        await handleSubmit('form/post');
    } catch (err) {
        alert('投稿に失敗しました');
    }
}

async function processLikeCount(): Promise<void> {
    const likes = document.querySelectorAll<HTMLElement>('.likeBtn');
    for (const btn of likes) {
        btn.addEventListener('click', async function () {
            const likeCount = this.querySelector('.likeCount') as HTMLElement;
            const postId = getPostId(this);

            const formData = new FormData();
            formData.append("postId", postId);

            const isLiked = btn.classList.contains('like');
            formData.append("status", isLiked ? 'true' : 'false');

            const res = await sendData('/post/like', formData);

            if (res?.success && "likeCount" in res) {
                likeCount.innerText = res['likeCount'].toString();
            } else if (res?.error) {
                alert(res.error);
            }

            btn.classList.toggle('like', !isLiked);
            btn.classList.toggle('unlike', isLiked);

            const iconPath = this.querySelector('.likeIcon svg path');
            if (iconPath instanceof SVGPathElement) {
                toggleLikeIcon(iconPath);
            }
        });
    }
}

function toggleLikeIcon(el: SVGPathElement): void {
    el.classList.toggle('like-red');
    el.classList.toggle('like-outline');
}

function getPostId(el: Element): string {
    const targetEl = el.closest("[data-postid]");
    if (!targetEl) throw new Error("Post ID element not found.");
    const postId = targetEl.getAttribute("data-postid");
    if (!postId) throw new Error("Post ID is null.");
    return postId;
}

function setPostIdToPostModal(postId: string): void {
    const postForm = document.getElementById('post-form') as HTMLFormElement;
    const inputHtml = `<input type="hidden" name="post_id" value="${postId}">`;
    postForm.insertAdjacentHTML('afterbegin', inputHtml);
}

/*
function setFormAction(path: string): void {
    const postForm = document.getElementById('post-form') as HTMLFormElement;
    postForm.setAttribute('action', path === 'clear' ? '' : path);
}
    */

async function handleCommentClick(): Promise<void> {
    const commentBtns = document.querySelectorAll<HTMLElement>('.commentBtn');
    for (const btn of commentBtns) {
        btn.addEventListener('click', async function () {
            const postId = getPostId(this);
            setPostIdToPostModal(postId);
            const result = await handleSubmit('post/comment');
            const countEl = this.querySelector('.commentCount');
            if (countEl) countEl.textContent = result['comment_count'];
            closePostModal();
        });
    }
}

function closePostModal(): void {
    const el = document.getElementById('crud-modal');
    if (el) {
        const modal = new Modal(el);
        modal.hide();
    }
}

async function main(): Promise<void> {
    await processLikeCount();
    handleCommentClick();
    postClickAction();
}

main();
