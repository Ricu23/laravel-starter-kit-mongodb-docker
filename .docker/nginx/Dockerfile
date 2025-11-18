FROM nginx:alpine

# Copy custom nginx configuration
COPY .docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Create necessary directories
RUN mkdir -p /var/log/nginx

# Expose port
EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
