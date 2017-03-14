# Finance

Get financial data from Yahoo! in your templates

```twig
{% set finance = craft.finance.get('GOOG')|first %}
{% if finance %}
	Label: {{ finance.label }}
	Name: {{ finance.name }}
	Asking price: {{ finance.ask|currency('USD') }}
	Bid price: {{ finance.bid|currency('USD') }}
	Change: {{ finance.change|currency('USD') }}
	Last traded: {{ finance.dateLastTraded|date('Y-m-d H:i:s') }}
{% endif %}
```

For more information on Yahoo!'s service, [click here](http://www.jarloo.com/yahoo_finance/).
