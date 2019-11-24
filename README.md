# AppPackager

AppPackager is an information security challenge in the Web category, and was presented to participants of [KAF CTF 2019](https://ctf.kipodafterfree.com)

## Challenge story

The developers had to rush this one out - they released an incomplete version, and still had some things on their TODO list.

## Challenge exploit

The user can inject a template url that has a zip with a broken symlink, which is unpacked, then gets repacked with the flag.

## Challenge solution

No need

## Building and installing

[Clone](https://github.com/NadavTasher/2019-AppPackager/archive/master.zip) the repository, then type the following command to build the container:
```bash
docker build . -t apppackager
```

To run the challenge, execute the following command:
```bash
docker run --rm -d -p 1000:80 apppackager
```

## Usage

You may now access the challenge interface through your browser: `http://localhost:1000`

## Flag

Flag is:
```flagscript
KAF{7h3se_f1a9z_dr1v33_m3_cr4zy}
```

## License
[MIT License](https://choosealicense.com/licenses/mit/)