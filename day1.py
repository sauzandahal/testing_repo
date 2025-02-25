# Function to mask the input
def maskify(input):
    if len(input) <= 4:
        return input  # Return the input as is if it's too short to mask
    return '#' * (len(input) - 4) + input[-4:]

# Get user input
user_input = input("Enter your sensitive information: ")

# Mask the user input
masked_input = maskify(user_input)
print(f"Masked output: {masked_input}")