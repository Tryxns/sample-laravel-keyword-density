FROM ubuntu:20.04

# Avoid warnings by switching to noninteractive
ENV DEBIAN_FRONTEND=noninteractive

# Use the default user 'developer'
ARG USERNAME=developer
ARG USER_UID=1001
ARG USER_GID=$USER_UID

# Create the user 'developer' with sudo access
RUN groupadd --gid $USER_GID $USERNAME \
	&& useradd --uid $USER_UID --gid $USER_GID -m $USERNAME \
	&& apt-get update \
	&& apt-get install -y sudo \
	&& echo $USERNAME ALL=\(root\) NOPASSWD:ALL > /etc/sudoers.d/$USERNAME \
	&& chmod 0440 /etc/sudoers.d/$USERNAME

# Install development tools
RUN apt-get install -y git build-essential

# Switch back to the dialog frontend for any additional apt-get installations
ENV DEBIAN_FRONTEND=dialog
