# php-lytro
A simple proof-of-concept library demonstrating a way to read sharable Lytro Picture Files (*-stk.lpf) with PHP and a sample light-weight JavaScript viewer. Everything you need to create your own self-hosted Lytro image gallery/photoviewer. 

## Why would you want this?! 
I hoped you could figure this out for yourself, but some examples:

* You could resize (or crop) images!
* You can add effects on images! (e.g. Black & White)
* Self-hosted, no pictures.lytro.com

## How to use?
This is just a proof of concept, which I will continue to update for a while, that you could implement in your own projects or create plugins with. Just imagine it; let's say you want to create a WordPress plugin that you could use to host your own Lytro photos on your (photo)blog. Now you can!

## Still a concept
It's still a concept so it is **still buggy**, also because PHP isn't the fastest language I implemented a caching system so you'd need to configure a cache directory (which needs to be writable) when using the script. But that's the worst part.

> While it is experimental, I will keep updating this repo for at least a little while.

## Credits
I've based this proof-of-concept PHP library on [lfptools](https://github.com/nrpatel/lfptools) which has been quite useful in understanding the LPF-format.