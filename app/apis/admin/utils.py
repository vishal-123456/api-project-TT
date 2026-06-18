import subprocess


def get_disk_usage(parameters: str):
    allowed_parameters = [
        "-a", "--all", "-B", "--block-size", "-h", "--human-readable", 
        "-H", "--si", "-i", "--inodes", "-k", "-l", "--local", 
        "--no-sync", "--output", "-P", "--portability", "--sync", 
        "--total", "-t", "--type", "-T", "-x", "--exclude-type"
    ]
    
    input_params = parameters.split()
    for param in input_params:
        if param not in allowed_parameters:
            raise ValueError(f"Invalid parameter: {parameters}")
    
    command = ["df", "-h"]
    command.extend(input_params)

    try:
        result = subprocess.run(
            command, stdout=subprocess.PIPE, stderr=subprocess.PIPE
        )
        usage = result.stdout.strip().decode()
    except:
        raise Exception("An unexpected error was observed")

    return usage
