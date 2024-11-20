# Use an official Node.js image as the base image
FROM node:18-alpine

# Set the working directory inside the container
WORKDIR /usr/src/app

# Copy package.json and package-lock.json from frontend directory
COPY ./frontend/ ./

# Install dependencies (npm install will install both npm and vite)
RUN npm install

# Copy the rest of the frontend files into the container


# Expose the port that Vite uses (default is 5173)
EXPOSE 5173

# Run the Vite development server when the container starts
CMD ["npm", "run", "dev"]
