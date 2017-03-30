# Getdem

Lets you filter by collections by multiple get params that may or may not exist.

Example:
```
{{ collection from="collection-handel" filter="getdem" params="param1|param2|param3" and="true" }}
  {{ partial:green_leader/preview }}
{{ /collection }}
```