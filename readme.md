# Getdem

Lets you filter by collections by multiple get params that may or may not exist.

URL format:
```
http://example.com/blog?taxonomy1=a&taxonomy2=b&taxonomy3=c
```

Example match any term (default):
```
{{ collection from="collection-handel" filter="getdem" params="taxonomy1|taxonomy2|taxonomy3" match="any" }}
  {{ partial:blog/preview }}
{{ /collection }}
```

Example all terms must match:
```
{{ collection from="collection-handel" filter="getdem" params="taxonomy1|taxonomy2|taxonomy3" match="all" }}
  {{ partial:blog/preview }}
{{ /collection }}
```

## Specify match paramater in url

URL format:
```
http://example.com/blog?taxonomy1=a&taxonomy2=b&taxonomy3=c&match=all
```

Filter format:
```
{{ collection from="collection-handel" filter="getdem" params="taxonomy1|taxonomy2|taxonomy3" :match="get:match" }}
  {{ partial:blog/preview }}
{{ /collection }}
```
