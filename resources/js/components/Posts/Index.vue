<template>
    <div>
        <div class="add-post">
            <input type="text" v-model="newPost.title" placeholder="Enter post title" />
            <textarea v-model="newPost.body" placeholder="Enter post body"></textarea>
            <input type="file" @change="handleFileUpload" accept="image/*" />
            <button @click="addPost">Add Post</button>
        </div>
        <div v-for="post in posts" :key="post.id" class="post">
            <h2>{{ post.title }}</h2>
            <p>{{ post.body }}</p>
            <img :src="post.image" alt="Post Image" />
            <div class="actions">
                <button @click="likePost(post.id)">Like</button>
                <button @click="commentPost(post.id)">Comment</button>
            </div>
            <div class="likes">Likes: {{ post.likes }}</div>
            <div class="comments">
                <div v-for="comment in post.comments" :key="comment.id" class="comment">
                    <p>{{ comment.text }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            posts: [],
            newPost: {
                title: '',
                body: '',
                image: ''
            }
        };
    },
    mounted() {
        this.fetchPosts();
    },
    handleFileUpload(event) {
        this.newPost.image = event.target.files[0];
    },
    methods: {
        fetchPosts() {
            axios.get('/api/posts')
                .then(response => {
                    this.posts = response.data;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        likePost(postId) {
            const post = this.posts.find(post => post.id === postId);
            post.likes++;
        },
        commentPost(postId) {
            const post = this.posts.find(post => post.id === postId);
            const commentText = prompt('Enter your comment:');
            post.comments.push({ id: post.comments.length + 1, text: commentText });
        },
        addPost() {

                const formData = new FormData();
                formData.append('title', this.newPost.title);
                formData.append('body', this.newPost.body);
                formData.append('image', this.newPost.image);

                axios.post('/api/store', formData,{
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }})
                    .then(response => {
                        this.fetchPosts(); // Fetch posts again to update the list
                        this.newPost.title = '';
                        this.newPost.body = '';
                        this.newPost.image = null;
                    })
                    .catch(error => {
                        console.error(error);
                    });

    },
            }
};
</script>

<style scoped>
.add-post {
    margin-bottom: 20px;
}

.post {
    border: 1px solid #ccc;
    padding: 10px;
    margin-bottom: 10px;
}

.actions {
    margin-top: 10px;
}

.likes {
    margin-top: 10px;
}

.comments {
    margin-top: 10px;
}

.comment {
    border: 1px solid #eee;
    padding: 5px;
    margin-top: 5px;
}
</style>
