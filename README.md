# Crypto API
A simple Laravel endpoint which calculates all possible conversion ways for provided currency dataset.
Developed as a programming job interview task. Utilizes DFS as the core algorithm.

## Installation Guide
Just refer to default Laravel installation guide here:
https://laravel.com/docs/6.x

## Usage Guide
The developed endpoint expects a regular HTTP POST request with URL and Body parameters set.
URL Params are: exchangeFrom and exchangeTo, which denotes the initial currency and the currency we want to convert it
to.

Whole functionality was tested using [Postman](https://www.getpostman.com/).
Since Laravel has the ability to parse pretty URLs, the URL parameters must be passed in the following manner:

http://crypto-api.test/api/currency/btc/eth


Where btc is currencyFrom and eth is currencyTo.

The body parameter must be a valid JSON array (otherwise, you'll get an error and Dalek invasion in some cases).
The array must hold objects with currencyFrom and currencyTo keys and respective values.

Example of the JSON array:

```[
   	{
   		"currencyFrom": "btc",
   		"currencyTo": "eth"
   	},
   	{
   		"currencyFrom": "ltc",
   		"currencyTo": "zec"
   	},
   	{
   		"currencyFrom": "zec",
   		"currencyTo": "eth"
   	},
   		{
   		"currencyFrom": "btc",
   		"currencyTo": "xmr"
   	},
   	{
   		"currencyFrom": "xmr",
   		"currencyTo": "bch"
   	},
   		{
   		"currencyFrom": "xmr",
   		"currencyTo": "xrp"
   	},
   	{
   		"currencyFrom": "xrp",
   		"currencyTo": "eth"
   	},
   	{
   		"currencyFrom": "bch",
   		"currencyTo": "eth"
   	}
   	
   ]
```

So, this in combination with the previously noted URL the endpoint will return us the following JSON array:
```
[ 
   [ 
      { 
         "currencyFrom":"btc",
         "currencyTo":"eth"
      }
   ],
   [ 
      { 
         "currencyFrom":"btc",
         "currencyTo":"xmr"
      },
      { 
         "currencyFrom":"xmr",
         "currencyTo":"bch"
      },
      { 
         "currencyFrom":"bch",
         "currencyTo":"eth"
      }
   ],
   [ 
      { 
         "currencyFrom":"btc",
         "currencyTo":"xmr"
      },
      { 
         "currencyFrom":"xmr",
         "currencyTo":"xrp"
      },
      { 
         "currencyFrom":"xrp",
         "currencyTo":"eth"
      }
   ]
]
```

Therefore, each sub-array is the path to the currency exchange result where objects are steps.
