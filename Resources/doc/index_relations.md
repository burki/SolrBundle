# Index OneToOne/ManyToOne relation

Given you have the following entity with a ManyToOne relation to `Category`.

```php
<?php

// ....

use FS\SolrBundle\Attribute as Solr;

/**
 * @ORM\Table()
 * @ORM\Entity
 */
#[Solr\Document()]
class Post
{
    /**
     * @var integer
     *
     * orm stuff
     */
    #[Solr\Id]
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    #[Solr\Field(type:"string")]
    private $title;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Acme\DemoBundle\Entity\Category", inversedBy="posts", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    #[Solr\Field(type:"string", getter:"getTitle")]
    private $category;

    // ... some getter / setter
}
```

You have now different ways to index the `category` relation:

- flat string representation
- full object

## Flat string representation

The important configuration is `#[Solr\Field(type:"string", getter:"getTitle")]`. This tells Solr to call `Category::getTitle()` when the `Post` is indexed.

```php

$category = new Category();
$category->setTitle('post category #1');

$post = new Post();
$post->setTitle('a post title');
$post->setCategory($category);

$em = $this->getDoctrine()->getManager();
$em->persist($post);
$em->flush();
```

### Quering the relation

```php
$posts = $this->get('solr.client')->getRepository('AcmeDemoBundle:Post')->findOneBy(array(
    'category' => 'post category #1'
));
```

# Index OneToMany relation

Given you have the following `Post` entity with a OneToMany relation to `Tag`.

Again you can index the collection in two ways:

- flat strings representation
- full objects

## flat strings representation

```php
<?php

// ....

use FS\SolrBundle\Attribute as Solr;

/**
 * Post
 *
 * @ORM\Table()
 * @ORM\Entity
 */
#[Solr\Document()]
class Post
{
    /**
     * // orm stuff
     */
    #[Solr\Id]
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=255)
     */
    #[Solr\Field(type:"string")]
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="Acme\DemoBundle\Entity\Tag", mappedBy="post", cascade={"persist"})
     */
    #[Solr\Field(type:"strings", getter:"getName")]
    private $tags;

    // ... some getter / setter
}
```

All `Tag`s will be transformed to a set of strings `#[Solr\Field(type:"strings", getter:"getName")]`.

```php
$post = new Post();
$post->setTitle($postTitle);
$post->setText('relation');
$post->setTags(array(
    new Tag('tag #1'),
    new Tag('tag #2'),
    new Tag('tag #3')
));

$em = $this->getDoctrine()->getManager();
$em->persist($post);
$em->flush();
```

Which will result in a document like this:

```json
"docs": [
  {
    "id": "post_391",
    "title_s": "post 25.03.2016",
    "text_t": "relation",
    "tags_ss": [
      "tag #1",
      "tag #2",
      "tag #3"
    ],
    "_version_": 1529771282767282200
  }
]
```

### Quering the strings collection

Now `Post` can be searched like this

```php
$posts = $this->get('solr.client')->getRepository('AcmeDemoBundle:Post')->findOneBy(array(
    'tags' => 'tag #1'
));
```

## Index full objects

Post entity:

```php
    /**
     * @ORM\OneToMany(targetEntity="Acme\DemoBundle\Entity\Tag", mappedBy="post", cascade={"persist"})
     */
    #[Solr\Field(type:"string", nestedClass:"Acme\DemoBundle\Entity\Tag")]
    private $tags;
```

Mark the `Tag` entity as Nested

```php
/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity
 */
#[Solr\Nested()]
class Tag
{
    /**
     * @var integer
     *
     * orm stuff
     */
    #[Solr\Id]
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    #[Solr\Field(type:"string")]
    private $name;

    // getter and setter
}
```

## Querying the collection

Now `Post` can be searched like this

```php
$posts = $this->get('solr.client')->getRepository('AcmeDemoBundle:Post')->findOneBy(array(
    'tags.name' => 'tag #1'
));
```
