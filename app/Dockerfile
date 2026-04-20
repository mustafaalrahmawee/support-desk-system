# ─────────────────────────────────────────────────────────────────────────────
# Stage 1: Build Vue.js app
# ─────────────────────────────────────────────────────────────────────────────
FROM node:22-alpine AS builder

WORKDIR /app

COPY package.json ./
RUN npm install

COPY . .
RUN npm run build

# ─────────────────────────────────────────────────────────────────────────────
# Stage 2: Development server (used in docker-compose)
# ─────────────────────────────────────────────────────────────────────────────
FROM node:22-alpine

WORKDIR /app

COPY package.json ./
RUN npm install

COPY . .

EXPOSE 5173

# npm install wird bei jedem Container-Start ausgeführt.
# So werden neue Pakete (aus dem gemounteten package.json) automatisch
# installiert — kein docker build nötig.
CMD ["sh", "-c", "npm install && npm run dev -- --host 0.0.0.0"]
