<?php
class Posts extends Controller
{

  public function __construct()
  {
    if (!isLoggedIn()) {
      redirect('pages');
    }
    $this->postModel = $this->model('Post');
  }

  public function index()
  {
    $posts = $this->postModel->getPosts();
    $data = [
      'posts' => $posts
    ];
    $this->view('posts/index', $data);
  }

  public function add()
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'user_id' => $_SESSION['user_id'],
        'title_err' => '',
        'body_err' => ''
      ];
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }
      if (empty($data['title_err']) && empty($data['body_err'])) {
        if ($this->postModel->insertPost($data['title'], $data['user_id'], $data['body'])) {
          flash('post_message', 'Post Added');
          redirect('posts');
        } else {
          die('Something went wrong');
        }

      } else {
        $this->view('posts/add', $data);
      }
    } else {
      $data = [
        'title' => '',
        'body' => '',
        'title_err' => '',
        'body_err' => ''
      ];
      $this->view('posts/add', $data);
    }
  }

  public function show($postId)
  {
    if ($this->postModel->getPostById($postId)) {
      $post = $this->postModel->getPostById($postId);
      $this->view('posts/show', $post);
    } else {
      die('Error..');
    }
  }

  public function edit($id)
  {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
      $data = [
        'id' => $id,
        'title' => trim($_POST['title']),
        'body' => trim($_POST['body']),
        'title_err' => '',
        'body_err' => ''
      ];
      if (empty($data['title'])) {
        $data['title_err'] = 'Please enter title';
      }
      if (empty($data['body'])) {
        $data['body_err'] = 'Please enter body text';
      }
      if (empty($data['title_err']) && empty($data['body_err'])) {
        if ($this->postModel->editPost($data)) {
          flash('post_message', 'Post Edited');
          redirect('posts');
        } else {
          die('Something went wrong');
        }
      } else {
        $this->view('posts/edit', $data);
      }
    } else {
      if ($this->postModel->getPostById($id)) {
        $post = $this->postModel->getPostById($id);
        if ($_SESSION['user_id'] != $post->userId) {
          redirect('posts');
        }
        $data = [
          'id' => $id,
          'title' => $post->title,
          'body' => $post->body,
          'title_err' => '',
          'body_err' => ''
        ];
        $this->view('posts/edit', $data);
      }
    }
  }

  public function delete($id)
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

      if ($this->postModel->getPostById($id)) {
        $post = $this->postModel->getPostById($id);
        if ($_SESSION['user_id'] != $post->userId) {
          redirect('posts');
        } else {
          if ($this->postModel->deletePost($id)) {
            flash('post_message', 'Post Deleted');
            redirect('posts');
          } else {
            die('Error..');
          }
        }
      }
    } else {
      redirect('posts');
    }
  }
}