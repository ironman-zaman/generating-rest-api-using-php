<?php
class Post{
    //DB Stuff
    private $conn;
    private $table = 'posts';

    //Post properties 
    public $id;
    public $category_id;
    public $title;
    public $body;
    public $created_at;


    //constructor with DB
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get Posts
    public function read(){
        //Create the query
        $query = 'SELECT 
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
                FROM
                    ' . $this->table . ' p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                ORDER BY
                 p.created_at DESC';

    //prepare statements
    $stmt = $this->conn->prepare($query);
    //Execute
    $stmt->execute();

    return $stmt;
    }

    //Get single post
    public function read_single(){
         //Create the query
        $query = 'SELECT 
                c.name as category_name,
                p.id,
                p.category_id,
                p.title,
                p.body,
                p.author,
                p.created_at
                FROM
                    ' . $this->table . ' p
                LEFT JOIN
                    categories c ON p.category_id = c.id
                WHERE 
                    p.id = ? 
                LIMIT 0,1';

        //prepare statements
        $stmt = $this->conn->prepare($query);

        //Bind ID
        $stmt->bindParam(1, $this->id);

        //Execute
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        //Set Properties
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    //Create Post
    public function create(){
        //Create Query
        $query = 'INSERT INTO '. $this->table . '
        SET 
         title = :title,
         body = :body,
         author = :author,
         category_id = :category_id';

         //Prepare statements
         $stmt = $this->conn->prepare($query);

         //Clean data
         $this->title = htmlspecialchars(strip_tags($this->title));
         $this->body = htmlspecialchars(strip_tags($this->body));
         $this->author = htmlspecialchars(strip_tags($this->author));
         $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        //Bind data
        $stmt->bindParam(':title',$this->title);
        $stmt->bindParam(':body',$this->body);
        $stmt->bindParam(':author',$this->author);
        $stmt->bindParam(':category_id',$this->category_id);

        //Execute Query
        if($stmt->execute()){
            return true;
        }

        //Print error if something goes wrong
        printf("Error %s.'\n",$stmt->error);

    }
}